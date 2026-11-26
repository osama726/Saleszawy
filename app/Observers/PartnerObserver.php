<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Partner;
use App\Services\CacheService;
use App\Support\CacheKeys;

final class PartnerObserver
{
    public function __construct(private readonly CacheService $cache) {}

    public function saved(Partner $partner): void
    {
        $this->forget();
    }

    public function deleted(Partner $partner): void
    {
        $this->forget();
    }

    private function forget(): void
    {
        $this->cache->forget(['partners'], CacheKeys::partners());
    }
}
