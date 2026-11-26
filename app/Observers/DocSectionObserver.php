<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\DocArticle;
use App\Models\DocSection;
use App\Services\CacheService;
use App\Support\CacheKeys;

final class DocSectionObserver
{
    public function __construct(private readonly CacheService $cache) {}

    public function saving(DocSection $section): void
    {
        // Slug is being renamed: the cached articles live under the old section slug.
        if ($section->exists && $section->isDirty('slug')) {
            $this->forgetArticles($section, (string) $section->getOriginal('slug'));
        }
    }

    public function saved(DocSection $section): void
    {
        $this->forgetTree();
        $this->forgetArticles($section, $section->slug);
    }

    public function deleted(DocSection $section): void
    {
        $this->forgetTree();
        $this->forgetArticles($section, $section->slug);
    }

    private function forgetTree(): void
    {
        $this->cache->forget(['docs'], CacheKeys::docTree());
    }

    /**
     * Each cached article embeds its section title/slug, so they must be refreshed too.
     */
    private function forgetArticles(DocSection $section, string $sectionSlug): void
    {
        DocArticle::withTrashed()
            ->where('section_id', $section->getKey())
            ->pluck('slug')
            ->each(fn (string $slug) => $this->cache->forget(['docs'], CacheKeys::docArticle($sectionSlug, $slug)));
    }
}
