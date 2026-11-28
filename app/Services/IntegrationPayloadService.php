<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Integration\ListPublishedIntegrationsAction;
use App\Http\Resources\IntegrationResource;
use App\Support\CacheKeys;
use App\Support\ResourcePayload;

final class IntegrationPayloadService
{
    private const TTL = 3600;

    public function __construct(
        private readonly CacheService $cache,
        private readonly ListPublishedIntegrationsAction $listIntegrations,
    ) {}

    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        return $this->cache->remember(
            ['integrations'],
            CacheKeys::integrations(),
            self::TTL,
            fn (): array => ResourcePayload::toArray(IntegrationResource::collection($this->listIntegrations->execute())),
        );
    }
}
