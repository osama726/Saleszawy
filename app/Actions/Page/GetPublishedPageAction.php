<?php

declare(strict_types=1);

namespace App\Actions\Page;

use App\Models\Page;

final class GetPublishedPageAction
{
    /**
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function execute(string $slug): Page
    {
        return Page::query()
            ->published()
            ->where('slug', $slug)
            ->with('visibleBlocks')
            ->firstOrFail();
    }
}
