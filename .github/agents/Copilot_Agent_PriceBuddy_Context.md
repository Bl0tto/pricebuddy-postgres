# 🏷️ Copilot Agent Context – PriceBuddy Application

**Purpose:** Deep-dive context for AI assistants working specifically with the PriceBuddy Laravel application codebase.

**GitHub Repository:** https://github.com/jez500/pricebuddy  
**Last Updated:** 2025-12-28

---

## 📋 Application Overview

### Technology Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| **Framework** | Laravel | Latest stable |
| **Admin Panel** | Filament | Latest |
| **Database** | PostgreSQL | Latest |
| **Language** | PHP | 8.x+ |
| **Frontend** | Livewire + Alpine.js | Via Filament |
| **Containerization** | Docker | Latest |

### Core Purpose
PriceBuddy is a self-hosted price tracking application that:
- Scrapes product information from e-commerce websites
- Tracks price changes over time
- Auto-creates store configurations from URLs
- Manages product catalogs across multiple stores
- Supports multi-currency pricing
- Provides price history and alerts

---

## 🏗️ Application Architecture

### Directory Structure
```
/app
├── /Services
│   ├── AutoCreateStore.php      # Auto-creation logic
│   └── ScraperService.php       # Web scraping
├── /Models
│   ├── Store.php                # Store entity
│   ├── Product.php              # Product entity
│   └── Price.php                # Price history
├── /Filament
│   └── /Resources               # Admin UI
└── /Http/Controllers
    └── ...

/config
├── price_buddy.php              # App-specific config
└── database.php

/database
├── /migrations
└── /seeders

/tests
├── /Unit
│   └── /Services
│       └── AutoCreateStoreTest.php
└── /Feature
```

---

## 🔍 Key Components

### 1. AutoCreateStore Service
**File:** `app/Services/AutoCreateStore.php`

**Purpose:** Automatically creates store configurations by scraping a product URL

**Key Methods:**
- `parseCurrency()` - Extracts currency from HTML (Issue #108/PR #109)
- `strategyParse()` - Parses title, price, image, currency from HTML
- `getStoreAttributes()` - Assembles store configuration from parsed data
- `create()` - Main entry point for auto-creation

**Strategy Pattern:**
Uses configurable CSS selectors and regex patterns to extract data:
```php
[
    'title' => ['meta[property="og:title"]|content', ...],
    'price' => ['[class*="price"]|innerText', ...],
    'image' => ['meta[property="og:image"]|content', ...],
    'currency' => ['meta[property="og:price:currency"]|content']
]
```

---

### 2. Store Model
**File:** `app/Models/Store.php`

**Schema:**
```sql
CREATE TABLE stores (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255),
    domain JSONB,              -- Array of domain objects
    scrape_strategy JSONB,     -- Scraping configuration
    locale_settings JSONB,     -- Currency and locale data
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Domain Storage Format:**
```json
[
    {"domain": "example.com"},
    {"domain": "www.example.com"}
]
```

**Key Methods:**
- `scopeDomainFilter($query, $domain)` - Filter stores by domain

---

### 3. Product Model
**File:** `app/Models/Product.php`

**Relationships:**
- `belongsTo(Store::class)`
- `hasMany(Price::class)` - Price history

---

## 🐛 Critical Bug: Domain Matching

### Issue Description
**GitHub Issue:** Related to domain normalization  
**Severity:** High – blocks product creation

### Problem Statement

When users create products from full URLs, the domain extraction and matching logic fails:

1. **Store Creation:**
   - Stores saved with domains: `[{"domain": "jw.com.au"}, {"domain": "www.jw.com.au"}]`
   - Multiple domain variations stored (with/without www)

2. **Product Creation:**
   - User provides full URL: `https://www.jw.com.au/products/example`
   - Domain extraction returns: `www.jw.com.au`
   - Database lookup fails to match properly
   - Results in error: "Domain does not belong to any stores"

3. **Root Causes:**
   - No consistent URL → domain normalization
   - `scopeDomainFilter()` receives null or mismatched domain
   - Duplicate stores created with domain variations
   - Domain array stored as JSONB not properly queried

### Confirmed Behavior

✅ **Working:**
- Store test scrape functionality
- Store exists in database
- Scraping strategies work correctly

❌ **Broken:**
- Product creation from full URLs
- Domain matching logic
- Duplicate prevention

---

## 🔧 Proposed Fix: Domain Normalization

### Strategy Overview

Implement comprehensive domain normalization at the entry point:

1. **Domain Extraction & Normalization:**
   - Extract domain from full URLs
   - Remove `www.` prefix consistently
   - Store canonical domain only
   - Handle edge cases (subdomains, ports, paths)

2. **Database Schema Update:**
   - Simplify domain storage to single canonical form
   - Add database index for performance
   - Migration to normalize existing data

3. **Matching Logic:**
   - Normalize input domains before comparison
   - Update `scopeDomainFilter()` to handle normalized domains
   - Prevent duplicate store creation

### Implementation Steps

#### Step 1: Create Domain Utility Class
**File:** `app/Services/DomainNormalizer.php`

```php
<?php

namespace App\Services;

class DomainNormalizer
{
    /**
     * Extract and normalize domain from URL
     *
     * @param string $url Full URL or domain
     * @return string Normalized domain (without www)
     */
    public static function normalize(string $url): string
    {
        // Parse URL
        $parsed = parse_url($url);
        $host = $parsed['host'] ?? $url;
        
        // Remove www. prefix
        $host = preg_replace('/^www\./', '', $host);
        
        // Convert to lowercase
        $host = strtolower($host);
        
        return $host;
    }
    
    /**
     * Extract domain from full URL
     *
     * @param string $url
     * @return string
     */
    public static function extractDomain(string $url): string
    {
        $parsed = parse_url($url);
        return $parsed['host'] ?? $url;
    }
}
```

#### Step 2: Update Store Model
**File:** `app/Models/Store.php`

```php
use App\Services\DomainNormalizer;

// Update scopeDomainFilter
public function scopeDomainFilter($query, $domain)
{
    if (empty($domain)) {
        return $query->whereNull('domain');
    }
    
    // Normalize input domain
    $normalizedDomain = DomainNormalizer::normalize($domain);
    
    // Search in JSONB array
    return $query->whereRaw(
        "domain @> ?::jsonb",
        [json_encode([['domain' => $normalizedDomain]])]
    );
}

// Add helper to get canonical domain
public function getCanonicalDomainAttribute()
{
    if (empty($this->domain)) {
        return null;
    }
    
    $domains = json_decode($this->domain, true);
    return $domains[0]['domain'] ?? null;
}
```

#### Step 3: Update AutoCreateStore Service
**File:** `app/Services/AutoCreateStore.php`

```php
use App\Services\DomainNormalizer;

protected function getStoreAttributes($url, $pageHtml)
{
    // Extract and normalize domain
    $domain = DomainNormalizer::normalize($url);
    
    // Parse strategies (including currency)
    $strategy = $this->strategyParse($pageHtml);
    
    return [
        'name' => $strategy['title'] ?? $domain,
        'domain' => [['domain' => $domain]], // Single canonical domain
        'scrape_strategy' => $strategy,
        'locale_settings' => [
            'currency' => $strategy['currency'] ?? 'USD',
            'locale' => $this->guessLocale($domain)
        ],
        'active' => true,
    ];
}

// Check for existing store before creation
public function create(string $url)
{
    $domain = DomainNormalizer::normalize($url);
    
    // Check if store exists
    $existingStore = Store::domainFilter($domain)->first();
    if ($existingStore) {
        return $existingStore;
    }
    
    // Create new store
    // ... existing logic
}
```

#### Step 4: Migration for Existing Data
**File:** `database/migrations/YYYY_MM_DD_normalize_store_domains.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Services\DomainNormalizer;
use App\Models\Store;

return new class extends Migration
{
    public function up()
    {
        // Normalize all existing store domains
        Store::all()->each(function ($store) {
            if (empty($store->domain)) {
                return;
            }
            
            $domains = json_decode($store->domain, true);
            if (empty($domains)) {
                return;
            }
            
            // Take first domain, normalize it
            $firstDomain = $domains[0]['domain'] ?? null;
            if ($firstDomain) {
                $normalized = DomainNormalizer::normalize($firstDomain);
                $store->domain = [['domain' => $normalized]];
                $store->save();
            }
        });
        
        // Add index for performance
        Schema::table('stores', function (Blueprint $table) {
            $table->index([DB::raw('(domain)')], 'stores_domain_idx');
        });
    }
    
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropIndex('stores_domain_idx');
        });
    }
};
```

#### Step 5: Update Tests
**File:** `tests/Unit/Services/AutoCreateStoreTest.php`

```php
use App\Services\DomainNormalizer;

public function test_domain_normalization()
{
    $inputs = [
        'https://www.example.com/product' => 'example.com',
        'http://example.com/page' => 'example.com',
        'www.example.com' => 'example.com',
        'example.com' => 'example.com',
        'https://shop.example.com' => 'shop.example.com',
    ];
    
    foreach ($inputs as $input => $expected) {
        $result = DomainNormalizer::normalize($input);
        $this->assertEquals($expected, $result, "Failed for: $input");
    }
}

public function test_duplicate_store_prevention()
{
    // Create store with normalized domain
    $store1 = $this->autoCreateStore->create('https://www.example.com/product1');
    
    // Try to create same store with different URL format
    $store2 = $this->autoCreateStore->create('https://example.com/product2');
    
    // Should return same store
    $this->assertEquals($store1->id, $store2->id);
}
```

---

## � Known Issues & Faults Tracker

### Issues Summary Table

| Issue ID | Title | Component | Severity | Status | Notes |
|----------|-------|-----------|----------|--------|-------|
| BUG-001 | Domain Matching Failure | AutoCreateStore / Store | ⚠️ High | ✅ FIXED | Duplicate stores created with www/non-www variants; no normalization |
| BUG-002 | scopeDomainFilter Returns 0 Results | Store Model | ⚠️ High | ✅ FIXED | Wrong JSON array format - used `['domain' => $x]` instead of `[['domain' => $x]]` |
| BUG-003 | CSS Selector Parsing Error | ScraperService | ⚠️ High | ❌ OPEN | Malformed CSS selector in scrape_strategy causes parsing failure |
| BUG-004 | PostgreSQL ISNULL Function Error | Database Query | ⚠️ High | ❌ OPEN | ISNULL is MySQL function, PostgreSQL requires IS NULL; compatibility issue |
| BUG-005 | UUID Validation Error | Notifications | ⚠️ Medium | ❌ OPEN | Invalid UUID format in notifications table validation |
| BUG-006 | Cloudflare Protection Blocking | Product Scraping | ℹ️ Info | N/A | Expected behavior - Cloudflare anti-bot challenge prevents scraping; not a bug |

### Detailed Issue Descriptions

#### ✅ BUG-001: Domain Matching Failure (FIXED)
**Root Cause:** No domain normalization; stores saved with multiple domain variants  
**Impact:** "Domain does not belong to any stores" error on product creation  
**Fix Applied:** Implemented `DomainNormalizer` service with centralized domain handling  
**Files Changed:**
- `app/Services/DomainNormalizer.php` (new)
- `app/Services/AutoCreateStore.php` (modified)
- `app/Models/Store.php` (modified)
- `database/migrations/2025_12_29_000000_normalize_store_domains.php` (new)

---

#### ✅ BUG-002: scopeDomainFilter Returns 0 Results (FIXED)
**Root Cause:** Incorrect PostgreSQL JSON array format in `whereJsonContains()`  
**Symptom:** `Store::domainFilter('jw.com.au')->count()` returns 0 despite stores existing  
**Fix Applied:** Changed from `['domain' => $x]` to `[['domain' => $first]]` (double array)  
**File Changed:** `app/Models/Store.php` line ~42  
**Validation:** ✅ Confirmed working - `Store::domainFilter('www.jw.com.au')->count()` now returns 3

---

#### ❌ BUG-003: CSS Selector Parsing Error (OPEN)
**Component:** `ScraperService.php` or selector strategy parsing  
**Symptom:** Product Search fails with CSS selector parsing error  
**Error Message:** "Malformed CSS selector in scrape_strategy"  
**Root Cause:** TBD - likely invalid CSS selector in store's `scrape_strategy` JSON  
**Investigation Steps:**
1. Check store's scrape_strategy JSON for invalid selectors
2. Test selector parsing logic in isolation
3. Add validation/sanitization for imported strategies
**Priority:** High - blocks product scraping

---

#### ❌ BUG-004: PostgreSQL ISNULL Function Error (OPEN)
**Component:** Database query - likely in Product Search or Scraper logic  
**Symptom:** PostgreSQL error "function isnull does not exist"  
**Root Cause:** Code using MySQL `ISNULL()` function; PostgreSQL uses `IS NULL`  
**Files to Check:**
- `ScraperService.php` - check for `ISNULL()` in raw queries
- Any migration files with raw SQL
- Product/Price model scopes
**Fix Approach:** Replace `ISNULL(column)` with `(column IS NULL)` or use Eloquent `whereNull()`  
**Priority:** High - database compatibility issue

---

#### ❌ BUG-005: UUID Validation Error (OPEN)
**Component:** Notifications table / UUID validation  
**Symptom:** Error on product/store operations mentioning invalid UUID format  
**Root Cause:** TBD - UUID column validation or invalid UUID generation  
**Files to Check:**
- `database/migrations` - UUID column definitions
- `app/Models/Notification.php` - UUID handling
- `app/Services` - UUID generation logic
**Priority:** Medium - affects notifications but not core functionality

---

#### ℹ️ BUG-006: Cloudflare Protection Blocking (NOT A BUG)
**Component:** Web scraping / Product URL handling  
**Symptom:** "Unable to auto create store" when accessing Cloudflare-protected URLs  
**Example:** computeralliance.com.au, cloudflare-protected sites  
**Root Cause:** Cloudflare anti-bot challenge requires JavaScript execution  
**Expected Behavior:** ✅ Correct - Scraper cannot bypass Cloudflare without headless browser  
**Workaround:** Manual store creation for protected sites  
**Status:** Design limitation, not a bug - requires headless browser (Puppeteer/Playwright) to fix

---

## �🔄 Currency Extraction (Issue #108 / PR #109)

### Context
**Upstream PR:** https://github.com/jez500/pricebuddy/pull/109  
**Status:** Merged upstream (needs local application)

### Summary of Changes

1. **New Method in AutoCreateStore:**
   ```php
   protected function parseCurrency($html)
   {
       // Extract currency from meta tags or regex
       // Returns 3-letter currency code (e.g., "AUD", "NZD")
   }
   ```

2. **Updated strategyParse():**
   - Now returns 4 fields: title, price, image, **currency**
   - Previously returned only 3 fields

3. **Updated Config:**
   **File:** `config/price_buddy.php`
   ```php
   'auto_create_store_strategies' => [
       'title' => ['meta[property="og:title"]|content', ...],
       'price' => ['[class*="price"]|innerText', ...],
       'image' => ['meta[property="og:image"]|content', ...],
       'currency' => ['meta[property="og:price:currency"]|content'], // NEW
   ],
   ```

4. **Test Updates:**
   - Updated fixtures with currency meta tags
   - Tests expect 4 strategy items instead of 3
   - Added AUD/NZD currency detection tests

### Integration Requirements

When implementing the domain fix:
- Ensure currency extraction still works
- Don't override existing `parseCurrency()` logic
- Maintain 4-field strategy return format
- Update locale_settings to use extracted currency

---

## 🧪 Testing Strategy

### Unit Tests

```bash
# Run all tests
docker exec -it pricebuddy php /app/vendor/bin/phpunit

# Run specific test file
docker exec -it pricebuddy php /app/vendor/bin/phpunit tests/Unit/Services/AutoCreateStoreTest.php

# Run specific test method
docker exec -it pricebuddy php /app/vendor/bin/phpunit --filter test_domain_normalization
```

### Manual Testing Checklist

1. **Domain Normalization:**
   - [ ] Test URL with www prefix
   - [ ] Test URL without www prefix
   - [ ] Test bare domain input
   - [ ] Test subdomain handling
   - [ ] Test invalid URLs

2. **Store Creation:**
   - [ ] Create store from new domain
   - [ ] Attempt duplicate creation (should return existing)
   - [ ] Verify domain stored correctly in DB
   - [ ] Check no duplicate stores exist

3. **Product Creation:**
   - [ ] Create product with full URL
   - [ ] Verify correct store association
   - [ ] Test with www/non-www variations
   - [ ] Confirm no "domain does not belong" errors

4. **Currency Detection:**
   - [ ] Test Australian store (AUD)
   - [ ] Test New Zealand store (NZD)
   - [ ] Test US store (USD default)
   - [ ] Verify locale_settings populated

5. **Database Integrity:**
   - [ ] Check domain JSONB structure
   - [ ] Verify no duplicate stores
   - [ ] Confirm indexing works
   - [ ] Test query performance

### Test URLs

```
# Australian stores
https://www.jw.com.au/products/example
https://www.kathmandu.com.au/products/example

# New Zealand stores
https://www.kathmandu.co.nz/products/example
https://www.thewarehouse.co.nz/products/example

# US stores
https://www.amazon.com/products/example
https://www.ebay.com/products/example
```

---

## 🔍 Database Queries for Debugging

### Check Store Domains

```sql
-- View all stores and their domains
SELECT 
    id,
    name,
    domain,
    jsonb_array_length(domain) as domain_count,
    created_at
FROM stores
ORDER BY created_at DESC;

-- Find stores with multiple domains
SELECT 
    id,
    name,
    domain
FROM stores
WHERE jsonb_array_length(domain) > 1;

-- Search for specific domain
SELECT *
FROM stores
WHERE domain @> '[{"domain": "jw.com.au"}]'::jsonb;

-- Find duplicate stores (same domain)
SELECT 
    domain->0->>'domain' as canonical_domain,
    COUNT(*) as store_count,
    array_agg(id) as store_ids
FROM stores
WHERE domain IS NOT NULL
GROUP BY domain->0->>'domain'
HAVING COUNT(*) > 1;
```

### Check Products

```sql
-- View products and their stores
SELECT 
    p.id,
    p.name,
    p.url,
    s.name as store_name,
    s.domain
FROM products p
JOIN stores s ON p.store_id = s.id
ORDER BY p.created_at DESC
LIMIT 20;

-- Find orphaned products (no matching store)
SELECT *
FROM products
WHERE store_id IS NULL
OR store_id NOT IN (SELECT id FROM stores);
```

---

## 🚀 Deployment Workflow

### Development Process

1. **Make Changes Locally:**
   ```bash
   # Edit files in VSCode
   # Changes auto-sync to Docker container (if volume mounted)
   ```

2. **Restart Container:**
   ```bash
   docker restart pricebuddy
   ```

3. **Check Logs:**
   ```bash
   docker logs -f pricebuddy
   ```

4. **Run Tests:**
   ```bash
   docker exec -it pricebuddy php /app/vendor/bin/phpunit
   ```

5. **Test in Browser:**
   - Navigate to http://192.168.10.243:8021
   - Test store creation
   - Test product creation
   - Verify functionality

6. **Database Validation:**
   ```bash
   docker exec -it postgres psql -U pricebuddy -d pricebuddy
   \d+ stores
   SELECT * FROM stores ORDER BY created_at DESC LIMIT 5;
   ```

### Creating Pull Request

1. **Create Feature Branch:**
   ```bash
   git checkout -b fix/domain-normalization
   ```

2. **Commit Changes:**
   ```bash
   git add app/Services/DomainNormalizer.php
   git add app/Services/AutoCreateStore.php
   git add app/Models/Store.php
   git add database/migrations/...
   git add tests/...
   git commit -m "Fix: Implement domain normalization to prevent duplicate stores"
   ```

3. **Push Branch:**
   ```bash
   git push origin fix/domain-normalization
   ```

4. **Create PR:**
   - Title: "Fix: Implement domain normalization to prevent duplicate stores"
   - Description:
     - Problem: Domain matching failures causing duplicate stores
     - Solution: Centralized domain normalization
     - Changes: List files modified
     - Testing: Manual + automated tests passed
     - References: Mention related issues

---

## 📚 Laravel/Filament Conventions

### Code Style
- Follow PSR-12 coding standards
- Use type hints for all method parameters and returns
- Document public methods with PHPDoc blocks
- Use meaningful variable names

### Model Conventions
- Use Eloquent relationships
- Define fillable/guarded properties
- Use mutators/accessors for data transformation
- Leverage query scopes for reusable queries

### Service Layer
- Keep services focused and single-purpose
- Inject dependencies via constructor
- Return consistent data structures
- Handle exceptions appropriately

### Testing
- Write tests for all new functionality
- Use factories for model creation
- Mock external dependencies
- Test edge cases and error conditions

---

## 💡 Common Pitfalls

### ❌ Don't Do This

```php
// Hard-coded domain extraction
$domain = str_replace('www.', '', parse_url($url)['host']);

// Direct JSON column manipulation
$store->domain = json_encode(['domain' => $domain]);

// Skipping normalization
Store::where('domain', 'like', "%$domain%")->first();
```

### ✅ Do This Instead

```php
// Use DomainNormalizer service
$domain = DomainNormalizer::normalize($url);

// Use model methods
$store->setDomain($domain);

// Use query scopes
Store::domainFilter($domain)->first();
```

---

## 🔗 Related Resources

- **Main Context:** [Copilot_Agent_HTPC_Context.md](Copilot_Agent_HTPC_Context.md)
- **Infrastructure:** [HTPC_Network.md](HTPC_Network.md)
- **GitHub Repo:** https://github.com/jez500/pricebuddy
- **Upstream PR #109:** https://github.com/jez500/pricebuddy/pull/109
- **Laravel Docs:** https://laravel.com/docs
- **Filament Docs:** https://filamentphp.com/docs
- **PostgreSQL JSON:** https://www.postgresql.org/docs/current/datatype-json.html

---

## 🎯 Quick Reference Commands

```bash
# Container access
docker exec -it pricebuddy bash

# Run migrations
docker exec -it pricebuddy php /app/artisan migrate

# Clear cache
docker exec -it pricebuddy php /app/artisan cache:clear

# Tinker REPL
docker exec -it pricebuddy php /app/artisan tinker

# Run specific test
docker exec -it pricebuddy php /app/vendor/bin/phpunit --filter test_name

# Database access
docker exec -it postgres psql -U pricebuddy -d pricebuddy

# View logs
docker logs -f pricebuddy

# Restart
docker restart pricebuddy
```

---

**Version:** 1.0  
**Maintainer:** System Administrator  
**GitHub:** jez500/pricebuddy
