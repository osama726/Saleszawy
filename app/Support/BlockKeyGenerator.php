<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\PageBlock;

final class BlockKeyGenerator
{
    /**
     * Returns $base if free on this page, otherwise $base-2, $base-3 ...
     */
    public static function generate(int $pageId, string $base, ?int $ignoreId = null): string
    {
        $taken = PageBlock::query()
            ->where('page_id', $pageId)
            ->when($ignoreId !== null, static fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('key', 'like', $base . '%')
            ->pluck('key')
            ->all();

        if (! in_array($base, $taken, true)) {
            return $base;
        }

        $suffix = 2;

        while (in_array($base . '-' . $suffix, $taken, true)) {
            $suffix++;
        }

        return $base . '-' . $suffix;
    }
}
