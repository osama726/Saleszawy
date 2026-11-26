<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Faq\ListPublishedFaqsAction;
use App\Http\Resources\FaqResource;
use App\Support\CacheKeys;
use App\Support\ResourcePayload;

final class FaqPayloadService
{
    private const TTL = 3600;

    public function __construct(
        private readonly CacheService $cache,
        private readonly ListPublishedFaqsAction $listFaqs,
    ) {}

    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(?string $group = null): array
    {
        return $this->cache->remember(
            ['faqs'],
            CacheKeys::faqs($group),
            self::TTL,
            fn (): array => ResourcePayload::toArray(FaqResource::collection($this->listFaqs->execute($group))),
        );
    }
}
