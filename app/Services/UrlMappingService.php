<?php

namespace App\Services;

use App\Contracts\UrlMappingServiceContract;
use App\Models\UrlMapping;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UrlMappingService implements UrlMappingServiceContract
{
    private CacheRepository $cache;

    public function __construct(CacheRepository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Maps a given original URL to a shortened URL if it does not already exist,
     * or retrieves the existing mapping from the cache or database.
     *
     * @param  string  $originalUrl  The original URL to map.
     * @return UrlMapping The UrlMapping instance containing the mapped data.
     *
     * @throws \Exception|\Psr\SimpleCache\InvalidArgumentException If an error occurs during the mapping or database process.
     */
    public function mapUrl(string $originalUrl): UrlMapping
    {
        $cacheKey = 'url_mapping_'.md5($originalUrl);

        // Check cache first
        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        try {
            // Fetch or create mapping
            $mapping = UrlMapping::firstOrCreate(
                ['url' => $originalUrl],
                ['short_key' => $this->generateUniqueShortKey()]
            );

            // Store in cache for 24 hours
            $this->cache->put($cacheKey, $mapping, now()->addHours(24));

            return $mapping;
        } catch (\Exception $e) {
            Log::error('Error mapping URL: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Retrieves the mapping for a given shortened URL. Fetches the mapping from
     * the cache if available, otherwise queries the database and stores the result
     * in the cache for later use.
     *
     * @param  string  $shortUrl  The shortened URL to retrieve the mapping for.
     * @return UrlMapping The UrlMapping instance containing the corresponding data.
     *
     * @throws \Exception If the shortened URL cannot be found or decoded.
     */
    public function retrieveShortUrl(string $shortUrl): UrlMapping
    {
        $shortKey = ltrim(parse_url($shortUrl, PHP_URL_PATH), '/');
        $cacheKey = 'short_key_'.$shortKey;

        // Check cache first
        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        try {
            // Fetch from database
            $mapping = UrlMapping::where('short_key', $shortKey)->firstOrFail();

            // Store in cache for 24 hours
            $this->cache->put($cacheKey, $mapping, now()->addHours(24));

            return $mapping;
        } catch (ModelNotFoundException $e) {
            Log::warning('Short URL not found: '.$shortUrl);
            throw new \Exception('Short URL not found.');
        } catch (\Exception $e) {
            Log::error('Error retrieving short URL: '.$e->getMessage());
            throw new \Exception('Failed to decode URL.');
        }
    }

    /**
     * Generate a unique short key.
     */
    private function generateUniqueShortKey(): string
    {
        do {
            $shortKey = Str::random(6);
        } while (UrlMapping::where('short_key', $shortKey)->exists());

        return $shortKey;
    }
}
