<?php

declare(strict_types=1);

namespace App\Actions\Doc;

use App\Models\DocArticle;
use App\Models\DocSection;
use Illuminate\Support\Collection;

final class GetDocTreeAction
{
    /**
     * Published sections as a tree (any depth). Two queries total, the tree is built in memory.
     * Articles carry no content: this feeds the docs sidebar.
     *
     * @return Collection<int, DocSection>
     */
    public function execute(): Collection
    {
        $sections = DocSection::query()->published()->ordered()->get();

        $articles = DocArticle::query()
            ->published()
            ->select(['id', 'section_id', 'title', 'slug', 'excerpt', 'version', 'sort_order'])
            ->ordered()
            ->get();

        $attach = static function (DocSection $section) use (&$attach, $sections, $articles): DocSection {
            $section->setRelation('articles', $articles->where('section_id', $section->id)->values());
            $section->setRelation('children', $sections->where('parent_id', $section->id)->map($attach)->values());

            return $section;
        };

        // Children of an unpublished parent are unreachable from the roots, so they stay hidden.
        return $sections->whereNull('parent_id')->map($attach)->values();
    }
}
