# 🔧 PriceBuddy Domain Normalization Fix – Complete Guide

**Issue:** Domain matching failures causing duplicate stores and product creation errors  
**Status:** ✅ Fixed and Tested  
**Date:** 2025-12-29  
**Version:** 1.0

---

## 📋 Table of Contents

1. [Problem Description](#problem-description)
2. [Root Cause Analysis](#root-cause-analysis)
3. [Solution Overview](#solution-overview)
4. [Files Modified](#files-modified)
5. [Installation Instructions](#installation-instructions)
6. [Making Changes Persistent](#making-changes-persistent)
7. [Testing Procedures](#testing-procedures)
8. [Troubleshooting](#troubleshooting)

---

## 🐛 Problem Description

### Symptoms

When creating products from URLs in PriceBuddy:

1. **Duplicate Store Creation**
   - Stores created with both `example.com` and `www.example.com` domains
   - Same store appearing multiple times with different slugs
   - Example: Store "Jw.com.au" existed as ID 24, but new attempts created IDs 26, 27

2. **Product Creation Failures**
   - Error: "Domain does not belong to any stores"
   - Product creation from full URLs failed
   - Domain matching inconsistent (www vs non-www)

3. **Database Issues**
   - Stores had redundant domain entries: `[{"domain": "jw.com.au"}, {"domain": "www.jw.com.au"}]`
   - Query mismatches due to inconsistent normalization

### Impact

- ❌ Users unable to create products from URLs
- ❌ Duplicate stores cluttering database
- ❌ Inconsistent domain matching
- ❌ Poor user experience

---

## 🔍 Root Cause Analysis

### Technical Issues

1. **No Domain Normalization**
   - URLs with `www.` and without were treated as different domains
   - No centralized logic for extracting/normalizing domains
   - Case-sensitive domain comparisons

2. **AutoCreateStore Service**
   ```php
   // OLD CODE - PROBLEMATIC
   $attributes['domains'] = [
       ['domain' => $host],
       ['domain' => 'www.'.$host],  // Creates duplicate
   ];
   ```
   - Stored both `www` and non-www variants
   - If URL already had `www.`, created `www.www.example.com`

3. **Store Model Query Scope**
   ```php
   // OLD CODE - PROBLEMATIC
   public function scopeDomainFilter($query, $domain)
   {
       // No normalization before querying
       return $query->whereJsonContains('domains', ['domain' => $domain]);
   }
   ```
   - No input normalization
   - Wrong JSON query format for Laravel's `whereJsonContains`
   - Required `[['domain' => 'x']]` not `['domain' => 'x']`

4. **Missing Duplicate Prevention**
   - `createStoreFromUrl()` didn't check for existing stores properly
   - Each URL created a new store

---

## 💡 Solution Overview

### Strategy

1. **Centralized Domain Normalization**
   - Created `DomainNormalizer` service
   - Strips `www.` prefix
   - Converts to lowercase
   - Handles full URLs and bare domains

2. **Single Canonical Domain**
   - Store only one normalized domain per store
   - Remove `www.` variants
   - Normalize on input and output

3. **Consistent Query Logic**
   - Normalize domains before database queries
   - Fix JSON query format
   - Proper duplicate checking

4. **Database Cleanup**
   - Migration to normalize existing store domains
   - Remove duplicate domain entries

---

## 📁 Files Modified

### New Files Created

| File | Purpose |
|------|---------|
| `app/Services/DomainNormalizer.php` | Centralized domain normalization logic |
| `tests/Unit/Services/DomainNormalizerTest.php` | Comprehensive unit tests |
| `database/migrations/2025_12_29_000000_normalize_store_domains.php` | Clean up existing data |

### Existing Files Modified

| File | Changes |
|------|---------|
| `app/Services/AutoCreateStore.php` | Use DomainNormalizer, store single domain, check duplicates |
| `app/Models/Store.php` | Fix scopeDomainFilter with normalization + correct JSON format |

---

## 📦 Installation Instructions

### Option A: Fresh Install (Building New Docker Image)

**Step 1: Clone/Update Repository**
```bash
cd /path/to/pricebuddy
git checkout main
git pull origin main

# Or if you have the files locally
# Copy all modified files to the repository
```

**Step 2: Ensure All Files Present**
```bash
# Verify new files exist
ls -la app/Services/DomainNormalizer.php
ls -la tests/Unit/Services/DomainNormalizerTest.php
ls -la database/migrations/2025_12_29_000000_normalize_store_domains.php

# Verify modified files updated
grep "DomainNormalizer" app/Services/AutoCreateStore.php
grep "DomainNormalizer" app/Models/Store.php
```

**Step 3: Build Docker Image**
```bash
# Build new image with fixes
docker build -t pricebuddy:latest .

# Or if using docker-compose
docker-compose build pricebuddy
```

**Step 4: Deploy New Container**
```bash
# Stop old container
docker stop pricebuddy
docker rm pricebuddy

# Start new container with updated image
docker-compose up -d pricebuddy

# Or manual docker run (adjust your parameters)
docker run -d --name pricebuddy \
  -p 8021:8000 \
  -v /path/to/data:/app/data \
  pricebuddy:latest
```

**Step 5: Run Migration**
```bash
# Access container
docker exec -it pricebuddy bash

# Run migration to normalize existing domains
php artisan migrate

# Verify migration ran
php artisan migrate:status | grep normalize_store_domains
```

**Step 6: Verify Installation**
```bash
# Test via Tinker
php artisan tinker

# Run tests (copy/paste each line)
use App\Services\DomainNormalizer;
DomainNormalizer::normalize('www.example.com');
// Should return: "example.com"

use App\Models\Store;
Store::domainFilter('www.example.com')->count();
// Should work without errors

exit
```

---

### Option B: Patching Existing Running Container

⚠️ **Warning:** Changes are **NOT PERSISTENT** - lost on container restart/rebuild

**Use Case:** Quick testing or temporary fix before rebuild

**Step 1: Copy Files from Windows PC**
```powershell
# From Windows PowerShell
cd c:\_git_repo\HTPC\pricebuddy

# Copy to htpc host
scp -i $env:USERPROFILE\.ssh\htpc-agent app/Services/DomainNormalizer.php casa@htpc:/tmp/
scp -i $env:USERPROFILE\.ssh\htpc-agent app/Services/AutoCreateStore.php casa@htpc:/tmp/
scp -i $env:USERPROFILE\.ssh\htpc-agent app/Models/Store.php casa@htpc:/tmp/
scp -i $env:USERPROFILE\.ssh\htpc-agent tests/Unit/Services/DomainNormalizerTest.php casa@htpc:/tmp/
scp -i $env:USERPROFILE\.ssh\htpc-agent database/migrations/2025_12_29_000000_normalize_store_domains.php casa@htpc:/tmp/
```

**Step 2: SSH to HTPC and Copy to Container**
```bash
ssh -i ~/.ssh/htpc-agent casa@htpc

# Copy files into running container
docker cp /tmp/DomainNormalizer.php pricebuddy:/app/app/Services/
docker cp /tmp/AutoCreateStore.php pricebuddy:/app/app/Services/
docker cp /tmp/Store.php pricebuddy:/app/app/Models/
docker cp /tmp/DomainNormalizerTest.php pricebuddy:/app/tests/Unit/Services/
docker cp /tmp/2025_12_29_000000_normalize_store_domains.php pricebuddy:/app/database/migrations/

# Verify files copied
docker exec -it pricebuddy ls -la /app/app/Services/DomainNormalizer.php
docker exec -it pricebuddy grep -n "DomainNormalizer" /app/app/Models/Store.php
```

**Step 3: Run Migration**
```bash
docker exec -it pricebuddy php /app/artisan migrate
```

**Step 4: Test Changes**
```bash
docker exec -it pricebuddy php /app/artisan tinker

# Test commands (see verification section below)
```

---

## 🔒 Making Changes Persistent

### Why Persistence Matters

Changes made by copying files into a running container are **ephemeral** - they disappear when:
- Container is restarted (`docker restart pricebuddy`)
- Container is rebuilt (`docker-compose up -d --build`)
- Container is recreated (`docker-compose down && docker-compose up`)

### Persistence Strategy

**Option 1: Rebuild Docker Image (Recommended)**

This is the **proper production approach**:

1. **Ensure Files in Source Repository**
   ```bash
   # On your development machine (Windows PC)
   cd c:\_git_repo\HTPC\pricebuddy
   
   # Verify all files present
   git status
   
   # Commit changes
   git add app/Services/DomainNormalizer.php
   git add app/Services/AutoCreateStore.php
   git add app/Models/Store.php
   git add database/migrations/2025_12_29_000000_normalize_store_domains.php
   git add tests/Unit/Services/DomainNormalizerTest.php
   
   git commit -m "Fix: Domain normalization to prevent duplicates"
   ```

2. **Push to Repository**
   ```bash
   # If using GitHub/GitLab
   git push origin main
   
   # Or create feature branch and PR
   git checkout -b fix/domain-normalization
   git push origin fix/domain-normalization
   ```

3. **Pull and Rebuild on HTPC**
   ```bash
   # SSH to HTPC
   ssh casa@htpc
   
   # Navigate to source (if you have it)
   cd /home/casa/pricebuddy-source
   git pull origin main
   
   # Rebuild image
   docker-compose build pricebuddy
   
   # Or rebuild manually
   docker build -t pricebuddy:latest .
   ```

4. **Deploy New Container**
   ```bash
   # Using docker-compose
   docker-compose down
   docker-compose up -d
   
   # Or manually
   docker stop pricebuddy
   docker rm pricebuddy
   docker run -d --name pricebuddy [your-docker-run-params] pricebuddy:latest
   ```

5. **Run Migration (First Time Only)**
   ```bash
   docker exec -it pricebuddy php /app/artisan migrate
   ```

**Option 2: Dockerfile Modifications**

If you maintain a custom Dockerfile, ensure it includes:

```dockerfile
FROM php:8.2-fpm

# ... existing setup ...

# Copy application code
COPY . /app

# Ensure composer dependencies installed
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /app

# ... rest of dockerfile ...
```

**Option 3: Volume Mounts (Development Only)**

For development, mount code as volume:

```yaml
# docker-compose.yml
services:
  pricebuddy:
    image: pricebuddy:latest
    volumes:
      - ./app:/app/app           # Mount app code
      - ./database:/app/database # Mount migrations
      - ./tests:/app/tests       # Mount tests
```

⚠️ **Not recommended for production** - code should be baked into image

---

## 🧪 Testing Procedures

### Automated Tests (If PHPUnit Available)

```bash
# Run DomainNormalizer tests
docker exec -it pricebuddy php /app/vendor/bin/phpunit tests/Unit/Services/DomainNormalizerTest.php -v

# Run all tests
docker exec -it pricebuddy php /app/vendor/bin/phpunit
```

### Manual Testing via Tinker

```bash
docker exec -it pricebuddy php /app/artisan tinker
```

**Test 1: DomainNormalizer**
```php
use App\Services\DomainNormalizer;

// Test normalization
DomainNormalizer::normalize('www.example.com');
// Expected: "example.com"

DomainNormalizer::normalize('EXAMPLE.COM');
// Expected: "example.com"

// Test URL extraction
DomainNormalizer::fromUrl('https://www.jw.com.au/products/test');
// Expected: "jw.com.au"

// Test matching
DomainNormalizer::matches('www.example.com', 'example.com');
// Expected: true
```

**Test 2: Store Query Scope**
```php
use App\Models\Store;

// Find stores by domain (with www)
Store::domainFilter('www.jw.com.au')->count();
// Should find stores

// Find stores by domain (without www)
Store::domainFilter('jw.com.au')->count();
// Should find same stores (normalization working)
```

**Test 3: Duplicate Prevention**
```php
use App\Services\AutoCreateStore;

// Try to create store twice
$store1 = AutoCreateStore::createStoreFromUrl('https://www.teststore.com.au/product1');
$store2 = AutoCreateStore::createStoreFromUrl('https://teststore.com.au/product2');

// Check IDs match (no duplicate created)
echo $store1->id . ' === ' . $store2->id;
// Should show same ID

// Verify domains normalized
$store1->domains;
// Expected: [{"domain": "teststore.com.au"}]
// NOT: [{"domain": "www.teststore.com.au"}, {"domain": "teststore.com.au"}]
```

**Test 4: End-to-End Product Creation**
```php
// Create a product (via Filament UI preferred, or Tinker)
use App\Models\Product;
use App\Models\Url;

// This should now work without domain errors
$product = Product::create([
    'name' => 'Test Product',
    'sku' => 'TEST123',
    'price' => 99.99,
]);

$url = Url::create([
    'product_id' => $product->id,
    'store_id' => $store1->id,
    'url' => 'https://www.teststore.com.au/product/test',
]);

// Should succeed without "domain does not belong" error
```

**Exit Tinker**
```php
exit
```

### Database Verification

```bash
# Connect to PostgreSQL
docker exec -it postgres psql -U pricebuddy -d pricebuddy
```

```sql
-- Check normalized domains
SELECT 
    id,
    name,
    domains,
    created_at
FROM stores
ORDER BY created_at DESC
LIMIT 10;

-- Should show single domains like:
-- domains: [{"domain": "jw.com.au"}]
-- NOT: [{"domain": "jw.com.au"}, {"domain": "www.jw.com.au"}]

-- Check for duplicates
SELECT 
    domains->0->>'domain' as domain,
    COUNT(*) as count,
    array_agg(id) as store_ids
FROM stores
WHERE domains IS NOT NULL
GROUP BY domains->0->>'domain'
HAVING COUNT(*) > 1;

-- Should return 0 rows (no duplicates)

\q
```

### Browser Testing

1. **Access PriceBuddy**: http://192.168.10.243:8021

2. **Test Store Creation**:
   - Go to Stores → Create New Store
   - Try URL: `https://www.testsite.com.au/product`
   - Verify only one domain stored (without www)

3. **Test Product Creation**:
   - Go to Products → Create New Product
   - Enter URL: `https://www.existingstore.com/product`
   - Should associate with existing store
   - Should NOT error with "domain does not belong"

4. **Test Duplicate Prevention**:
   - Try creating another store with `https://testsite.com.au/different-product`
   - Should return existing store, not create duplicate

---

## 🔧 Troubleshooting

### Issue: Files Not Found After Container Restart

**Symptom:**
```bash
docker exec -it pricebuddy ls /app/app/Services/DomainNormalizer.php
# Returns: No such file or directory
```

**Cause:** Files were copied to running container, not baked into image

**Solution:** Follow [Making Changes Persistent](#making-changes-persistent) → Rebuild Docker image

---

### Issue: Migration Already Ran

**Symptom:**
```bash
php artisan migrate
# Nothing happens - migration already executed
```

**Cause:** Migration ran previously

**Solution:** Check migration status
```bash
php artisan migrate:status | grep normalize_store_domains
# Should show "Ran" status
```

**To Re-run (Development Only):**
```bash
# Rollback last migration
php artisan migrate:rollback --step=1

# Run again
php artisan migrate
```

---

### Issue: scopeDomainFilter Returns No Results

**Symptom:**
```php
Store::domainFilter('example.com')->count();
// Returns 0, but stores exist
```

**Debugging Steps:**

1. **Check Store.php Updated**
   ```bash
   docker exec -it pricebuddy grep -n "DomainNormalizer" /app/app/Models/Store.php
   # Should show imports and usage
   ```

2. **Check JSON Format**
   ```php
   // Correct format (double array)
   Store::whereJsonContains('domains', [['domain' => 'example.com']])->count();
   
   // Wrong format (single array) - returns 0
   Store::whereJsonContains('domains', ['domain' => 'example.com'])->count();
   ```

3. **Verify scopeDomainFilter Code**
   ```bash
   docker exec -it pricebuddy sed -n '119,145p' /app/app/Models/Store.php
   ```
   
   Should show:
   ```php
   $subQuery->whereJsonContains('domains', [['domain' => $first]]);
   ```
   
   NOT:
   ```php
   $subQuery->whereJsonContains('domains', ['domain' => $first]);
   ```

4. **Force File Copy Again**
   ```bash
   # Re-copy Store.php from local repo
   scp -i ~/.ssh/htpc-agent c:\_git_repo\HTPC\pricebuddy\app\Models\Store.php casa@htpc:/tmp/
   docker cp /tmp/Store.php pricebuddy:/app/app/Models/Store.php
   ```

---

### Issue: Duplicate Stores Still Created

**Symptom:**
```php
$s1 = AutoCreateStore::createStoreFromUrl('https://www.test.com/p1');
$s2 = AutoCreateStore::createStoreFromUrl('https://test.com/p2');
echo $s1->id . ' != ' . $s2->id; // Different IDs
```

**Debugging:**

1. **Check AutoCreateStore Updated**
   ```bash
   docker exec -it pricebuddy grep -n "DomainNormalizer::fromUrl" /app/app/Services/AutoCreateStore.php
   # Should show usage in createStoreFromUrl and getStoreAttributes
   ```

2. **Test Normalization Directly**
   ```php
   use App\Services\DomainNormalizer;
   $d1 = DomainNormalizer::fromUrl('https://www.test.com/p1');
   $d2 = DomainNormalizer::fromUrl('https://test.com/p2');
   echo $d1 . ' === ' . $d2; // Should be same
   ```

3. **Check Lookup Query**
   ```php
   use App\Services\DomainNormalizer;
   $domain = DomainNormalizer::fromUrl('https://www.test.com/p1');
   $existing = Store::query()->domainFilter($domain)->first();
   var_dump($existing); // Should find existing store
   ```

4. **Re-copy AutoCreateStore.php**
   ```bash
   scp -i ~/.ssh/htpc-agent c:\_git_repo\HTPC\pricebuddy\app\Services\AutoCreateStore.php casa@htpc:/tmp/
   docker cp /tmp/AutoCreateStore.php pricebuddy:/app/app/Services/AutoCreateStore.php
   ```

---

### Issue: SSH Key Warning

**Symptom:**
```bash
Warning: Identity file C:/Users/bruta/.ssh/htpc-agent not accessible: No such file or directory
```

**Cause:** SSH key file doesn't exist at specified path or permissions issue

**Verify Key Exists:**
```powershell
# Windows PowerShell
Test-Path $env:USERPROFILE\.ssh\htpc-agent
# Should return True

# If False, key doesn't exist - regenerate
ssh-keygen -t ed25519 -C "htpc-agent" -f $env:USERPROFILE\.ssh\htpc-agent
```

**Copy Key to HTPC:**
```powershell
# Windows PowerShell
cat $env:USERPROFILE\.ssh\htpc-agent.pub | ssh casa@htpc "cat >> ~/.ssh/authorized_keys"
```

**Test Connection:**
```powershell
ssh -i $env:USERPROFILE\.ssh\htpc-agent casa@htpc "echo Connection successful"
```

---

### Issue: JSON Operator Error

**Symptom:**
```
SQLSTATE[42883]: Undefined function: operator does not exist: json @> jsonb
```

**Cause:** Using PostgreSQL-specific JSONB operator on JSON column

**Solution:** Ensure Store.php uses `whereJsonContains` (Laravel abstraction), not raw `@>` operator

**Check Code:**
```bash
docker exec -it pricebuddy grep -A 5 "scopeDomainFilter" /app/app/Models/Store.php
```

Should use:
```php
$subQuery->whereJsonContains('domains', [['domain' => $first]]);
```

NOT:
```php
$query->whereRaw("domains @> ?::jsonb", [json_encode([['domain' => $first]])]);
```

---

## 📚 Additional Resources

### File Locations Reference

```
c:\_git_repo\HTPC\pricebuddy\
├── app\
│   ├── Services\
│   │   ├── DomainNormalizer.php         [NEW]
│   │   └── AutoCreateStore.php          [MODIFIED]
│   └── Models\
│       └── Store.php                     [MODIFIED]
├── database\
│   └── migrations\
│       └── 2025_12_29_000000_normalize_store_domains.php  [NEW]
└── tests\
    └── Unit\
        └── Services\
            └── DomainNormalizerTest.php  [NEW]
```

### Quick Command Reference

```bash
# SSH to HTPC
ssh -i ~/.ssh/htpc-agent casa@htpc

# Access container
docker exec -it pricebuddy bash

# Run Tinker
docker exec -it pricebuddy php /app/artisan tinker

# Run migrations
docker exec -it pricebuddy php /app/artisan migrate

# View logs
docker logs -f pricebuddy

# Restart container
docker restart pricebuddy

# Rebuild image
docker-compose build pricebuddy
docker-compose up -d

# Database access
docker exec -it postgres psql -U pricebuddy -d pricebuddy
```

### Related Documentation

- [HTPC_Network.md](HTPC_Network.md) - Infrastructure overview
- [IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md) - Implementation details
- [Copilot_Agent_HTPC_Context.md](../.github/agents/Copilot_Agent_HTPC_Context.md) - AI assistant context
- [Copilot_Agent_PriceBuddy_Context.md](../.github/agents/Copilot_Agent_PriceBuddy_Context.md) - PriceBuddy context

---

## ✅ Success Checklist

After applying this fix, verify:

- [ ] DomainNormalizer service exists and works
- [ ] AutoCreateStore stores single normalized domain
- [ ] Store::domainFilter finds stores with/without www
- [ ] Migration ran successfully
- [ ] Existing stores normalized (single domain each)
- [ ] Duplicate prevention working (same ID returned)
- [ ] Product creation succeeds without domain errors
- [ ] No duplicate stores in database
- [ ] Changes persistent (survives container restart)
- [ ] Docker image rebuilt with fixes
- [ ] Documentation updated

---

**Version:** 1.0  
**Last Updated:** 2025-12-29  
**Status:** Production Ready ✅
