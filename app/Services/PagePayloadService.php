<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Page\GetPublishedPageAction;
use App\Http\Resources\PageResource;
use App\Support\CacheKeys;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Builds (and caches) the public JSON payload of a page.
 * Single blocks are read from the same cached payload, so invalidation stays in one place.
 */
final class PagePayloadService
{
    private const TTL = 3600;

    public function __construct(
        private readonly CacheService $cache,
        private readonly GetPublishedPageAction $getPublishedPage,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function page(string $slug): array
    {
        return $this->cache->remember(
            ['pages'],
            CacheKeys::page($slug),
            self::TTL,
            fn (): array => json_decode(
                (string) json_encode(PageResource::make($this->getPublishedPage->execute($slug))),
                true,
                512,
                JSON_THROW_ON_ERROR,
            ),
        );
    }

    /**
     * @return array<string, mixed>
     *
     * @throws NotFoundHttpException
     */
    public function block(string $slug, string $key): array
    {
        foreach ($this->page($slug)['blocks'] as $block) {
            if ($block['key'] === $key) {
                return $block;
            }
        }

        throw new NotFoundHttpException();
    }
}
