<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Page;
use App\Models\PageBlock;
use App\Services\CacheService;
use App\Support\CacheKeys;

final class PageBlockObserver
{
    public function __construct(private readonly CacheService $cache) {}

    public function saved(PageBlock $block): void
    {
        $this->forget($block);
    }

    public function deleted(PageBlock $block): void
    {
        $this->forget($block);
    }

    private function forget(PageBlock $block): void
    {
        $slug = Page::withTrashed()->whereKey($block->page_id)->value('slug');

        if ($slug !== null) {
            $this->cache->forget(['pages'], CacheKeys::page($slug));
        }
    }
}
