<?php

declare(strict_types=1);

namespace App\Actions\Doc;

use App\Models\DocArticle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class SearchDocArticlesAction
{
    private const FULLTEXT_DRIVERS = ['mysql', 'mariadb'];

    /**
     * FULLTEXT on MySQL/MariaDB (+ title LIKE for short words the index ignores),
     * plain LIKE everywhere else (sqlite in tests). Title matches come first.
     *
     * @return Collection<int, DocArticle>
     */
    public function execute(string $term, int $limit = 20): Collection
    {
        $like = '%' . addcslashes($term, '\\%_') . '%';

        $query = DocArticle::query()
            ->published()
            ->whereHas('section', static fn ($q) => $q->published())
            ->with('section:id,title,slug');

        $fullText = in_array($query->getQuery()->getConnection()->getDriverName(), self::FULLTEXT_DRIVERS, true);

        $query->where(static function (Builder $q) use ($term, $like, $fullText): void {
            if ($fullText) {
                $q->whereFullText(['title', 'content'], $term)->orWhere('title', 'like', $like);

                return;
            }

            $q->where('title', 'like', $like)->orWhere('content', 'like', $like);
        });

        return $query
            ->orderByRaw('CASE WHEN title LIKE ? THEN 0 ELSE 1 END', [$like])
            ->ordered()
            ->limit($limit)
            ->get();
    }
}
