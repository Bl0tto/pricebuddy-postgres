<?php

namespace App\Services;

use App\Actions\CreateStoreAction;
use App\Enums\ScraperService;
use App\Enums\ScraperStrategyType;
use App\Models\Store;
use App\Services\Helpers\CurrencyHelper;
use Closure;
use Exception;
use Illuminate\Support\Uri;
use Jez500\WebScraperForLaravel\Exceptions\DomSelectorException;
use Jez500\WebScraperForLaravel\Facades\WebScraper;
use Jez500\WebScraperForLaravel\WebScraperInterface;
use Symfony\Component\DomCrawler\Crawler;
use InvalidArgumentException;

class AutoCreateStore
{
    public const string DEFAULT_SCRAPER = ScraperService::Http->value;

    public const string ALT_SCRAPER = ScraperService::Api->value;

    protected WebScraperInterface $scraperService;

    protected array $strategies = [];

    public bool $logErrors = true;

    public function __construct(protected string $url, public ?string $html = null, string $scraper = self::DEFAULT_SCRAPER, int $timeout = 30)
    {
        $this->strategies = config('price_buddy.auto_create_store_strategies', []);

        if (empty($html)) {
            $scraper_instance = WebScraper::make($scraper)
                ->setConnectTimeout($timeout)
                ->setRequestTimeout($timeout);

            // Configure API scraper endpoint if needed
            if ($scraper === self::ALT_SCRAPER) {
                $scraper_instance->setScraperApiBaseUrl(
                    config('price_buddy.scraper_api_url', 'http://scraper:3000')
                );
            }

            $this->scraperService = $scraper_instance->from($url)->get();
            $this->html = $this->scraperService->getBody();

            // Detect Cloudflare challenge and retry with API scraper
            if ($this->isCloudflareChallenge($this->html) && $scraper !== self::ALT_SCRAPER) {
                $this->scraperService = WebScraper::make(self::ALT_SCRAPER)
                    ->setConnectTimeout($timeout)
                    ->setRequestTimeout($timeout)
                    ->setScraperApiBaseUrl(
                        config('price_buddy.scraper_api_url', 'http://scraper:3000')
                    )
                    ->from($url)
                    ->get();
                $this->html = $this->scraperService->getBody();
            }
        } else {
            $this->scraperService = WebScraper::make($scraper)->setBody($this->html);
        }
    }
    
    protected function isCloudflareChallenge(string $html): bool
    {
        return str_contains($html, 'Just a moment...') 
            || str_contains($html, 'Enable JavaScript and cookies to continue')
            || str_contains($html, 'challenge-platform');
    }

    public static function new(string $url, ?string $html = null, string $scraper = self::DEFAULT_SCRAPER, int $timeout = 30): self
    {
        return resolve(static::class, [
            'url' => $url,
            'html' => $html,
            'scraper' => $scraper,
            'timeout' => $timeout,
        ]);
    }

    public static function canAutoCreateFromUrl(string $url, int $timeout = 30): bool
    {
        return ! is_null(self::new($url, timeout: $timeout)->getStoreAttributes());
    }

    public static function createStoreFromUrl(string $url): ?Store
    {
        // Check if store exists by normalized domain
        try {
            $domain = DomainNormalizer::fromUrl($url);
        } catch (InvalidArgumentException $e) {
            return null;
        }

        if ($existing = Store::query()->domainFilter($domain)->first()) {
            return $existing;
        }

        $attributes = self::new($url)->getStoreAttributes();

        return $attributes
            ? (new CreateStoreAction)($attributes)
            : null;
    }

    public function getStoreAttributes(): ?array
    {
        $strategy = $this->strategyParse();
        $schemaOrg = ScraperStrategyType::SchemaOrg->value;

        // Exit if required fields are missing, noting that schemaOrg doesn't requrie a value.
        if (
            (data_get($strategy, 'title.type') !== $schemaOrg && empty($strategy['title']['value'])) ||
            (data_get($strategy, 'price.type') !== $schemaOrg && empty($strategy['price']['value']))
        ) {
            $this->errorLog('Unable to auto create store', [
                'url' => $this->url,
                'strategy' => $strategy,
                'html' => $this->html,
            ]);

            return null;
        }

        $attributes = [
            'user_id' => auth()->id(),
        ];

        // Extract and normalize domain
        try {
            $normalizedDomain = DomainNormalizer::fromUrl($this->url);
        } catch (InvalidArgumentException $e) {
            $this->errorLog('Failed to extract domain from URL', [
                'url' => $this->url,
                'error' => $e->getMessage(),
            ]);
            return null;
        }

        // Store only the normalized domain (without www)
        $attributes['domains'] = [
            ['domain' => $normalizedDomain],
        ];

        $attributes['name'] = ucfirst($normalizedDomain);

        $attributes['scrape_strategy'] = collect($this->strategyParse())
            ->mapWithKeys(function ($value, $key) {
                return [
                    $key => collect($value)->only('type', 'value')->all(),
                ];
            })
            ->toArray();

        // Use API scraper if Cloudflare protection detected
        $scraperService = $this->isCloudflareChallenge($this->html) 
            ? ScraperService::Api->value 
            : ScraperService::Http->value;

        $attributes['settings'] = [
            'scraper_service' => $scraperService,
            'scraper_service_settings' => '',
            'test_url' => $this->url,
            'locale_settings' => [
                'locale' => CurrencyHelper::getLocale(),
                'currency' => CurrencyHelper::getCurrency(),
            ],
        ];

        return $attributes;
    }

    public function strategyParse(): array
    {
        return [
            'title' => $this->parseTitle(),
            'price' => $this->parsePrice(),
            'image' => $this->parseImage(),
        ];
    }

    protected function parseTitle(): ?array
    {
        if ($match = $this->attemptSchemaOrg('title')) {
            return $match;
        }

        if ($match = $this->attemptSelectors($this->getStrategy('title', 'selector'))) {
            return $match;
        }

        if ($match = $this->attemptRegex($this->getStrategy('title', 'regex'))) {
            return $match;
        }

        return [];
    }

    protected function parsePrice(): ?array
    {
        $validateCallback = function ($value) {
            return CurrencyHelper::toFloat($value);
        };

        if ($match = $this->attemptSchemaOrg('price', $validateCallback)) {
            return $match;
        }

        if ($match = $this->attemptSelectors($this->getStrategy('price', 'selector'), $validateCallback)) {
            return $match;
        }

        if ($match = $this->attemptRegex($this->getStrategy('price', 'regex'), $validateCallback)) {
            return $match;
        }

        return [];
    }

    protected function parseImage(): ?array
    {
        if ($match = $this->attemptSchemaOrg('image')) {
            return $match;
        }

        if ($match = $this->attemptSelectors($this->getStrategy('image', 'selector'))) {
            return $match;
        }

        if ($match = $this->attemptRegex($this->getStrategy('image', 'regex'))) {
            return $match;
        }

        return [];
    }

    protected function attemptSchemaOrg(string $field, ?Closure $validateValue = null): ?array
    {
        $extracted = SchemaOrgService::parseSchemaOrg($this->scraperService->getSchemaOrg(), $field);

        $value = is_null($validateValue)
            ? $extracted
            : $validateValue($extracted);

        return ! empty($value)
            ? ['type' => ScraperStrategyType::SchemaOrg->value, 'value' => null, 'data' => $value]
            : null;
    }

    protected function attemptSelectors(array $selectors, ?Closure $validateValue = null): ?array
    {
        $value = null;
        $workingSelector = null;

        $dom = new Crawler($this->html);

        foreach ($selectors as $selector) {
            if ($value) {
                break;
            }

            $selectorSettings = ScrapeUrl::parseSelector($selector);
            $realSelector = $selectorSettings[0];
            $method = $selectorSettings[1] ?? 'text';
            $args = $selectorSettings[2] ?? [];

            try {
                $results = $dom->filter($realSelector)
                    ->each(function (Crawler $node) use ($method, $args, $validateValue) {
                        $extracted = call_user_func_array([$node, $method], $args);

                        return is_null($validateValue)
                            ? $extracted
                            : $validateValue($extracted);
                    });

                $value = data_get($results, '0');

                if ($value) {
                    $workingSelector = $selector;
                }
            } catch (DomSelectorException $e) {
                // not found.
            }
        }

        return ! empty($workingSelector)
            ? ['type' => 'selector', 'value' => $workingSelector, 'data' => $value]
            : null;
    }

    protected function attemptRegex(array $regexes, ?Closure $validateValue = null): ?array
    {
        $value = null;
        $workingRegex = null;

        foreach ($regexes as $regex) {
            if ($value) {
                break;
            }

            try {
                preg_match_all($regex, $this->html, $matches);
                $extracted = data_get($matches, '1.0');

                $value = is_null($validateValue)
                    ? $extracted
                    : $validateValue($extracted);

                if ($value) {
                    $workingRegex = $regex;
                }
            } catch (Exception $e) {
            }
        }

        return ! empty($workingRegex)
            ? ['type' => 'regex', 'value' => $workingRegex, 'data' => $value]
            : null;
    }

    protected function getStrategy(string $fieldName, string $type): ?array
    {
        return data_get($this->strategies, $fieldName.'.'.$type);
    }

    protected function getStrategyValue(string $fieldName, string $type): ?string
    {
        return data_get($this->getStrategy($fieldName, $type), 'value');
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setLogErrors(bool $logErrors): self
    {
        $this->logErrors = $logErrors;

        return $this;
    }

    protected function errorLog(string $message, array $data = []): void
    {
        if (! $this->logErrors) {
            return;
        }

        logger()->error($message, $data);
    }
}
