<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Doc\GetDocTreeAction;
use App\Actions\Doc\GetPublishedDocArticleAction;
use App\Http\Resources\DocArticleResource;
use App\Http\Resources\DocSectionResource;
use App\Support\CacheKeys;
use App\Support\ResourcePayload;

/**
 * Builds (and caches) the public payloads of the docs portal: the sidebar tree and single articles.
 * Search is intentionally not cached (see SearchDocArticlesAction).
 */
final class DocPayloadService
{
    private const TTL = 3600;

    public function __construct(
        private readonly CacheService $cache,
        private readonly GetDocTreeAction $getTree,
        private readonly GetPublishedDocArticleAction $getArticle,
    ) {}

    /**
     * @return array<int, array<string, mixed>>
     */
    public function tree(): array
    {
        return $this->cache->remember(
            ['docs'],
            CacheKeys::docTree(),
            self::TTL,
            fn (): array => ResourcePayload::toArray(DocSectionResource::collection($this->getTree->execute())),
        );
    }

    /**
     * @return array<string, mixed>
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function article(string $sectionSlug, string $articleSlug): array
    {
        return $this->cache->remember(
            ['docs'],
            CacheKeys::docArticle($sectionSlug, $articleSlug),
            self::TTL,
            fn (): array => ResourcePayload::toArray(
                DocArticleResource::make($this->getArticle->execute($sectionSlug, $articleSlug)),
            ),
        );
    }
}
