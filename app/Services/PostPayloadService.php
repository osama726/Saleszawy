<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\Post\GetPublishedPostAction;
use App\Actions\Post\ListPublishedPostsAction;
use App\Http\Resources\PostCategoryResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostSummaryResource;
use App\Support\CacheKeys;
use App\Support\ResourcePayload;

final class PostPayloadService
{
    private const TTL = 3600;

    public function __construct(
        private readonly CacheService $cache,
        private readonly ListPublishedPostsAction $listPosts,
        private readonly GetPublishedPostAction $getPost,
    ) {}

    /**
     * @return array{
     *     categories: array<int, array<string, mixed>>,
     *     tags: array<int, string>,
     *     posts: array<int, array<string, mixed>>
     * }
     */
    public function all(
        ?string $categorySlug = null,
        ?string $tag = null,
        ?string $search = null,
        ?bool $featured = null,
        ?int $limit = null,
    ): array {
        $payload = $this->cache->remember(
            ['posts'],
            CacheKeys::postsIndex(),
            self::TTL,
            function (): array {
                $data = $this->listPosts->execute();
                $posts = ResourcePayload::toArray(PostSummaryResource::collection($data['posts']));

                $tags = [];
                foreach ($posts as $post) {
                    foreach ($post['tags'] ?? [] as $item) {
                        if (is_string($item) && $item !== '' && ! in_array($item, $tags, true)) {
                            $tags[] = $item;
                        }
                    }
                }

                return [
                    'categories' => ResourcePayload::toArray(PostCategoryResource::collection($data['categories'])),
                    'tags' => $tags,
                    'posts' => $posts,
                ];
            },
        );

        $posts = $payload['posts'];

        if ($categorySlug !== null) {
            $posts = array_filter(
                $posts,
                static fn (array $post): bool => ($post['category']['slug'] ?? null) === $categorySlug,
            );
        }

        if ($tag !== null) {
            $posts = array_filter(
                $posts,
                static fn (array $post): bool => in_array($tag, $post['tags'] ?? [], true),
            );
        }

        if ($featured !== null) {
            $posts = array_filter(
                $posts,
                static fn (array $post): bool => (bool) ($post['is_featured'] ?? false) === $featured,
            );
        }

        if ($search !== null) {
            $posts = array_filter(
                $posts,
                static fn (array $post): bool => mb_stripos((string) ($post['title'] ?? ''), $search) !== false
                    || mb_stripos((string) ($post['excerpt'] ?? ''), $search) !== false
                    || mb_stripos(implode(' ', $post['tags'] ?? []), $search) !== false,
            );
        }

        $posts = array_values($posts);

        if ($limit !== null) {
            $posts = array_slice($posts, 0, $limit);
        }

        $payload['posts'] = $posts;

        return $payload;
    }

    /**
     * @return array<string, mixed>
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show(string $slug): array
    {
        return $this->cache->remember(
            ['posts'],
            CacheKeys::post($slug),
            self::TTL,
            fn (): array => ResourcePayload::toArray(PostResource::make($this->getPost->execute($slug))),
        );
    }
}
