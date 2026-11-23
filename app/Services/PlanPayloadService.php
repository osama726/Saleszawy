<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Plan\ListPublishedPlansAction;
use App\Http\Resources\PlanResource;
use App\Support\CacheKeys;
use App\Support\ResourcePayload;

final class PlanPayloadService
{
    private const TTL = 3600;

    public function __construct(
        private readonly CacheService $cache,
        private readonly ListPublishedPlansAction $listPlans,
    ) {}

    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        return $this->cache->remember(
            ['plans'],
            CacheKeys::plans(),
            self::TTL,
            fn (): array => ResourcePayload::toArray(PlanResource::collection($this->listPlans->execute())),
        );
    }
}
