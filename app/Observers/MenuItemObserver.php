<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\MenuItem;
use App\Services\CacheService;
use App\Support\CacheKeys;

final class MenuItemObserver
{
    public function __construct(private readonly CacheService $cache) {}

    public function saved(MenuItem $item): void
    {
        $this->cache->forget(['layout'], CacheKeys::layout());
    }

    public function deleted(MenuItem $item): void
    {
        $this->cache->forget(['layout'], CacheKeys::layout());
    }
}
