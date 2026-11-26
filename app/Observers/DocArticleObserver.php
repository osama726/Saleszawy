<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\DocArticle;
use App\Models\DocSection;
use App\Services\CacheService;
use App\Support\CacheKeys;

final class DocArticleObserver
{
    public function __construct(private readonly CacheService $cache) {}

    public function saving(DocArticle $article): void
    {
        // Renamed or moved to another section: drop the entry stored under the old address.
        if ($article->exists && $article->isDirty(['slug', 'section_id'])) {
            $this->forgetArticle((int) $article->getOriginal('section_id'), (string) $article->getOriginal('slug'));
        }
    }

    public function saved(DocArticle $article): void
    {
        $this->forgetCurrent($article);
    }

    public function deleted(DocArticle $article): void
    {
        $this->forgetCurrent($article);
    }

    public function restored(DocArticle $article): void
    {
        $this->forgetCurrent($article);
    }

    public function forceDeleted(DocArticle $article): void
    {
        $this->forgetCurrent($article);
    }

    private function forgetCurrent(DocArticle $article): void
    {
        $this->forgetArticle((int) $article->section_id, $article->slug);
        $this->cache->forget(['docs'], CacheKeys::docTree());
    }

    private function forgetArticle(int $sectionId, string $slug): void
    {
        $sectionSlug = DocSection::query()->whereKey($sectionId)->value('slug');

        if ($sectionSlug !== null) {
            $this->cache->forget(['docs'], CacheKeys::docArticle((string) $sectionSlug, $slug));
        }
    }
}
