<?php

declare(strict_types=1);

namespace App\Actions\Post;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Database\Eloquent\Collection;

final class ListPublishedPostsAction
{
    /**
     * @return array{categories: Collection<int, PostCategory>, posts: Collection<int, Post>}
     */
    public function execute(): array
    {
        $categories = PostCategory::query()
            ->withCount(['posts' => static fn ($query) => $query->published()])
            ->ordered()
            ->get();

        $posts = Post::query()
            ->with(['category', 'author', 'media'])
            ->published()
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get();

        return [
            'categories' => $categories,
            'posts' => $posts,
        ];
    }
}
