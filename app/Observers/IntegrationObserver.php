<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Integration;
use App\Services\CacheService;
use App\Support\CacheKeys;

final class IntegrationObserver
{
    public function __construct(private readonly CacheService $cache) {}

    public function saved(Integration $integration): void
    {
        $this->forget();
    }

    public function deleted(Integration $integration): void
    {
        $this->forget();
    }

    private function forget(): void
    {
        $this->cache->forget(['integrations'], CacheKeys::integrations());
    }
}
