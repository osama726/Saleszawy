<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Plan;
use App\Services\CacheService;
use App\Support\CacheKeys;

final class PlanObserver
{
    public function __construct(private readonly CacheService $cache) {}

    public function saved(Plan $plan): void
    {
        $this->forget();
    }

    public function deleted(Plan $plan): void
    {
        $this->forget();
    }

    private function forget(): void
    {
        $this->cache->forget(['plans'], CacheKeys::plans());
    }
}
