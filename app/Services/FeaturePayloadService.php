<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Feature\ListPublishedFeaturesAction;
use App\Http\Resources\FeatureCategoryResource;
use App\Http\Resources\FeatureResource;
use App\Support\CacheKeys;
use App\Support\ResourcePayload;

final class FeaturePayloadService
{
    private const TTL = 3600;

    public function __construct(
        private readonly CacheService $cache,
        private readonly ListPublishedFeaturesAction $listFeatures,
    ) {}

    /**
     * @return array{categories: array<int, array<string, mixed>>, features: array<int, array<string, mixed>>}
     */
    public function all(?string $group = null, ?string $categorySlug = null): array
    {
        $payload = $this->cache->remember(
            ['features'],
            CacheKeys::features($group),
            self::TTL,
            function () use ($group): array {
                $data = $this->listFeatures->execute($group);

                return [
                    'categories' => ResourcePayload::toArray(FeatureCategoryResource::collection($data['categories'])),
                    'features' => ResourcePayload::toArray(FeatureResource::collection($data['features'])),
                ];
            },
        );

        if ($categorySlug !== null) {
            $payload['features'] = array_values(array_filter(
                $payload['features'],
                static fn (array $feature): bool => ($feature['category']['slug'] ?? null) === $categorySlug,
            ));
        }

        return $payload;
    }
}
