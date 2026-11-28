<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\PostCategory;
use App\Services\CacheService;
use App\Support\CacheKeys;

final class PostCategoryObserver
{
    public function __construct(private readonly CacheService $cache) {}

    public function saved(PostCategory $category): void
    {
        $this->cache->forget(['posts'], CacheKeys::postsIndex());
    }

    public function deleted(PostCategory $category): void
    {
        $this->cache->forget(['posts'], CacheKeys::postsIndex());
    }
}
