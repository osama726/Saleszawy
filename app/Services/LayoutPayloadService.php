<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Layout\GetLayoutAction;
use App\Http\Resources\FooterResource;
use App\Http\Resources\HeaderResource;
use App\Support\CacheKeys;
use App\Support\ResourcePayload;

/**
 * One cached payload for header + footer; /layout/header and /layout/footer slice it.
 */
final class LayoutPayloadService
{
    private const TTL = 3600;

    public function __construct(
        private readonly CacheService $cache,
        private readonly GetLayoutAction $getLayout,
    ) {}

    /**
     * @return array{header: array<string, mixed>, footer: array<string, mixed>}
     */
    public function all(): array
    {
        return $this->cache->remember(
            ['layout'],
            CacheKeys::layout(),
            self::TTL,
            function (): array {
                $layout = $this->getLayout->execute();

                return [
                    'header' => ResourcePayload::toArray(HeaderResource::make($layout)),
                    'footer' => ResourcePayload::toArray(FooterResource::make($layout)),
                ];
            },
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function header(): array
    {
        return $this->all()['header'];
    }

    /**
     * @return array<string, mixed>
     */
    public function footer(): array
    {
        return $this->all()['footer'];
    }
}
