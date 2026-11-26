<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Partner\ListPublishedPartnersAction;
use App\Http\Resources\PartnerResource;
use App\Support\CacheKeys;
use App\Support\ResourcePayload;

final class PartnerPayloadService
{
    private const TTL = 3600;

    public function __construct(
        private readonly CacheService $cache,
        private readonly ListPublishedPartnersAction $listPartners,
    ) {}

    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        return $this->cache->remember(
            ['partners'],
            CacheKeys::partners(),
            self::TTL,
            fn (): array => ResourcePayload::toArray(PartnerResource::collection($this->listPartners->execute())),
        );
    }
}
