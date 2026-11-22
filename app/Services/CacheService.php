<?php

declare(strict_types=1);

namespace App\Services;

use Closure;
use Illuminate\Cache\Repository;
use Illuminate\Support\Facades\Cache;

/**
 * Wrapper around the cache store. Uses tags when the store supports them
 * (Redis/Memcached) and falls back to plain keys otherwise (file/database).
 */
final class CacheService
{
    /**
     * @param  array<int, string>  $tags
     */
    public function remember(array $tags, string $key, int $ttl, Closure $callback): mixed
    {
        return $this->store($tags)->remember($key, $ttl, $callback);
    }

    /**
     * @param  array<int, string>  $tags
     */
    public function forget(array $tags, string $key): void
    {
        $this->store($tags)->forget($key);
    }

    /**
     * @param  array<int, string>  $tags
     */
    private function store(array $tags): Repository
    {
        $cache = Cache::store();

        return $cache->supportsTags() ? $cache->tags($tags) : $cache;
    }
}
