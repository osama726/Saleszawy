<?php

declare(strict_types=1);

namespace App\Actions\Doc;

use App\Models\DocArticle;

final class GetPublishedDocArticleAction
{
    /**
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function execute(string $sectionSlug, string $articleSlug): DocArticle
    {
        return DocArticle::query()
            ->published()
            ->where('slug', $articleSlug)
            ->whereHas('section', static fn ($query) => $query->published()->where('slug', $sectionSlug))
            ->with('section:id,title,slug')
            ->firstOrFail();
    }
}
