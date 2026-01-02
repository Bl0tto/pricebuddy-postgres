<?php

namespace App\Services;

/**
 * Service for normalizing and extracting domains from URLs
 * 
 * This service ensures consistent domain handling throughout the application
 * by removing www prefixes and normalizing URL formats.
 */
class DomainNormalizer
{
    /**
     * Extract domain from a full URL
     *
     * Handles various URL formats and returns just the domain/host portion
     *
     * @param string $url Full URL or domain
     * @return string The domain/host (e.g., "www.example.com" or "example.com")
     * @throws \InvalidArgumentException If URL is invalid
     */
    public static function extractDomain(string $url): string
    {
        $url = trim($url);

        // Handle URLs without protocol
        if (! str_contains($url, '://')) {
            $url = 'https://' . $url;
        }

        $parsed = parse_url($url);

        if (! isset($parsed['host']) || empty($parsed['host'])) {
            throw new \InvalidArgumentException("Invalid URL provided: {$url}");
        }

        return $parsed['host'];
    }

    /**
     * Normalize a domain by removing www prefix
     *
     * Converts "www.example.com" to "example.com"
     * Also converts to lowercase for consistency
     *
     * @param string $domain Domain or full URL
     * @return string Normalized domain without www prefix
     * @throws \InvalidArgumentException If domain/URL is invalid
     */
    public static function normalize(string $domain): string
    {
        // Extract domain if it's a full URL
        if (str_contains($domain, '://') || str_contains($domain, '/')) {
            $domain = self::extractDomain($domain);
        }

        $domain = trim($domain);

        // Remove www. prefix if present
        if (str_starts_with($domain, 'www.')) {
            $domain = substr($domain, 4);
        }

        // Convert to lowercase for consistency
        $domain = strtolower($domain);

        return $domain;
    }

    /**
     * Check if a domain matches another domain (ignoring www)
     *
     * @param string $domain1 First domain
     * @param string $domain2 Second domain
     * @return bool True if domains match after normalization
     */
    public static function matches(string $domain1, string $domain2): bool
    {
        try {
            return self::normalize($domain1) === self::normalize($domain2);
        } catch (\InvalidArgumentException) {
            return false;
        }
    }

    /**
     * Normalize a domain from a full URL for storage
     *
     * Useful for extracting and normalizing in one operation
     *
     * @param string $url Full URL
     * @return string Normalized domain without www prefix
     * @throws \InvalidArgumentException If URL is invalid
     */
    public static function fromUrl(string $url): string
    {
        $domain = self::extractDomain($url);
        return self::normalize($domain);
    }
}
