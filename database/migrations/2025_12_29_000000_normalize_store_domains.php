<?php

use App\Services\DomainNormalizer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Normalize all existing store domains to remove duplicates (www and non-www variants)
        // This ensures stores are stored with only the canonical domain (without www prefix)
        
        $stores = DB::table('stores')->get();

        foreach ($stores as $store) {
            if (empty($store->domains)) {
                continue;
            }

            $domains = json_decode($store->domains, true);
            if (empty($domains) || ! is_array($domains)) {
                continue;
            }

            // Extract the first domain and normalize it
            $firstDomain = data_get($domains, '0.domain');
            
            if (empty($firstDomain)) {
                continue;
            }

            try {
                $normalized = DomainNormalizer::normalize($firstDomain);
                
                // Store only the normalized domain
                $newDomains = [['domain' => $normalized]];
                
                DB::table('stores')
                    ->where('id', $store->id)
                    ->update(['domains' => json_encode($newDomains)]);
            } catch (\InvalidArgumentException $e) {
                // Log or skip invalid domains
                \Log::warning("Failed to normalize domain for store {$store->id}", [
                    'domain' => $firstDomain,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reliably reverse this migration as we've discarded data
        // Manual restore from backup would be needed
        \Log::warning('Domain normalization migration reversed. Store domains may need manual restoration.');
    }
};
