<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Page;
use App\Services\CacheService;
use App\Support\CacheKeys;

final class PageObserver
{
    public function __construct(private readonly CacheService $cache) {}

    public function saving(Page $page): void
    {
        // Slug is being renamed: drop the cache entry stored under the old slug.
        if ($page->exists && $page->isDirty('slug')) {
            $this->cache->forget(['pages'], CacheKeys::page((string) $page->getOriginal('slug')));
        }
    }

    public function saved(Page $page): void
    {
        $this->cache->forget(['pages'], CacheKeys::page($page->slug));
    }

    public function deleted(Page $page): void
    {
        $this->cache->forget(['pages'], CacheKeys::page($page->slug));
    }

    public function restored(Page $page): void
    {
        $this->cache->forget(['pages'], CacheKeys::page($page->slug));
    }
}
