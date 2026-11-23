<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Menu;
use App\Services\CacheService;
use App\Support\CacheKeys;

final class MenuObserver
{
    public function __construct(private readonly CacheService $cache) {}

    public function saved(Menu $menu): void
    {
        $this->cache->forget(['layout'], CacheKeys::layout());
    }

    public function deleted(Menu $menu): void
    {
        $this->cache->forget(['layout'], CacheKeys::layout());
    }
}
