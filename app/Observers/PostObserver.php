<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Post;
use App\Services\CacheService;
use App\Support\CacheKeys;

final class PostObserver
{
    public function __construct(private readonly CacheService $cache) {}

    public function saving(Post $post): void
    {
        if ($post->exists && $post->isDirty('slug')) {
            $original = $post->getOriginal('slug');
            if (is_string($original) && $original !== '') {
                $this->cache->forget(['posts'], CacheKeys::post($original));
            }
        }
    }

    public function saved(Post $post): void
    {
        $this->forget($post);
    }

    public function deleted(Post $post): void
    {
        $this->forget($post);
    }

    public function restored(Post $post): void
    {
        $this->forget($post);
    }

    private function forget(Post $post): void
    {
        $this->cache->forget(['posts'], CacheKeys::postsIndex());
        $this->cache->forget(['posts'], CacheKeys::post($post->slug));
    }
}
