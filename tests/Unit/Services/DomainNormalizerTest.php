<?php

namespace Tests\Unit\Services;

use App\Services\DomainNormalizer;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DomainNormalizerTest extends TestCase
{
    /**
     * Test extracting domain from various URL formats
     */
    public function test_extract_domain_from_full_url(): void
    {
        $this->assertEquals('example.com', DomainNormalizer::extractDomain('https://example.com/path'));
        $this->assertEquals('www.example.com', DomainNormalizer::extractDomain('https://www.example.com/path'));
        $this->assertEquals('sub.example.com', DomainNormalizer::extractDomain('https://sub.example.com/path'));
    }

    /**
     * Test extracting domain from URL without protocol
     */
    public function test_extract_domain_without_protocol(): void
    {
        $this->assertEquals('example.com', DomainNormalizer::extractDomain('example.com'));
        $this->assertEquals('www.example.com', DomainNormalizer::extractDomain('www.example.com'));
    }

    /**
     * Test extracting domain with port
     */
    public function test_extract_domain_with_port(): void
    {
        $this->assertEquals('example.com', DomainNormalizer::extractDomain('https://example.com:8080/path'));
    }

    /**
     * Test normalization removes www prefix
     */
    public function test_normalize_removes_www(): void
    {
        $this->assertEquals('example.com', DomainNormalizer::normalize('www.example.com'));
        $this->assertEquals('example.com', DomainNormalizer::normalize('example.com'));
        $this->assertEquals('sub.example.com', DomainNormalizer::normalize('www.sub.example.com'));
    }

    /**
     * Test normalization converts to lowercase
     */
    public function test_normalize_converts_to_lowercase(): void
    {
        $this->assertEquals('example.com', DomainNormalizer::normalize('EXAMPLE.COM'));
        $this->assertEquals('example.com', DomainNormalizer::normalize('Example.Com'));
        $this->assertEquals('example.com', DomainNormalizer::normalize('WWW.EXAMPLE.COM'));
    }

    /**
     * Test normalization of full URLs
     */
    public function test_normalize_full_url(): void
    {
        $this->assertEquals('example.com', DomainNormalizer::normalize('https://www.example.com/path'));
        $this->assertEquals('example.com', DomainNormalizer::normalize('https://example.com/path?query=value'));
        $this->assertEquals('sub.example.com', DomainNormalizer::normalize('https://www.sub.example.com/path'));
    }

    /**
     * Test domain matching ignores www prefix
     */
    public function test_matches_ignores_www(): void
    {
        $this->assertTrue(DomainNormalizer::matches('example.com', 'www.example.com'));
        $this->assertTrue(DomainNormalizer::matches('www.example.com', 'example.com'));
        $this->assertTrue(DomainNormalizer::matches('example.com', 'example.com'));
        $this->assertFalse(DomainNormalizer::matches('example.com', 'other.com'));
    }

    /**
     * Test domain matching with URLs
     */
    public function test_matches_with_urls(): void
    {
        $this->assertTrue(DomainNormalizer::matches(
            'https://example.com/path',
            'https://www.example.com/other'
        ));
        $this->assertTrue(DomainNormalizer::matches(
            'https://www.example.com/path',
            'example.com'
        ));
    }

    /**
     * Test domain matching is case insensitive
     */
    public function test_matches_case_insensitive(): void
    {
        $this->assertTrue(DomainNormalizer::matches('Example.Com', 'example.com'));
        $this->assertTrue(DomainNormalizer::matches('WWW.Example.COM', 'example.com'));
    }

    /**
     * Test fromUrl convenience method
     */
    public function test_from_url(): void
    {
        $this->assertEquals('jw.com.au', DomainNormalizer::fromUrl('https://www.jw.com.au/products/example'));
        $this->assertEquals('example.com', DomainNormalizer::fromUrl('https://example.com'));
        $this->assertEquals('kathmandu.co.nz', DomainNormalizer::fromUrl('https://www.kathmandu.co.nz/products'));
    }

    /**
     * Test invalid URL throws exception
     */
    public function test_invalid_url_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        DomainNormalizer::extractDomain('not a url at all');
    }

    /**
     * Test real-world domain examples
     */
    public function test_real_world_domains(): void
    {
        // Australian stores
        $this->assertEquals('jw.com.au', DomainNormalizer::fromUrl('https://www.jw.com.au/products/example'));
        $this->assertEquals('kathmandu.com.au', DomainNormalizer::fromUrl('https://www.kathmandu.com.au/products/example'));

        // New Zealand stores
        $this->assertEquals('kathmandu.co.nz', DomainNormalizer::fromUrl('https://www.kathmandu.co.nz/products/example'));
        $this->assertEquals('thewarehouse.co.nz', DomainNormalizer::fromUrl('https://www.thewarehouse.co.nz/products/example'));

        // US stores
        $this->assertEquals('amazon.com', DomainNormalizer::fromUrl('https://www.amazon.com/products/example'));
        $this->assertEquals('ebay.com', DomainNormalizer::fromUrl('https://www.ebay.com/products/example'));
    }

    /**
     * Test subdomains are preserved
     */
    public function test_subdomains_preserved(): void
    {
        $this->assertEquals('shop.example.com', DomainNormalizer::normalize('shop.example.com'));
        $this->assertEquals('shop.example.com', DomainNormalizer::normalize('www.shop.example.com'));
        $this->assertNotEquals('shop.example.com', DomainNormalizer::normalize('www.example.com'));
    }

    /**
     * Test matches returns false for invalid input gracefully
     */
    public function test_matches_returns_false_for_invalid(): void
    {
        $this->assertFalse(DomainNormalizer::matches('invalid url', 'also invalid'));
    }
}
