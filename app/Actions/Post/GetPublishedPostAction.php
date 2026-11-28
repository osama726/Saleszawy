<?php

declare(strict_types=1);

namespace App\Actions\Post;

use App\Models\Post;

final class GetPublishedPostAction
{
    public function execute(string $slug): Post
    {
        return Post::query()
            ->with(['category', 'author', 'media'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();
    }
}
