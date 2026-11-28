<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Post
 */
final class PostSummaryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category' => $this->whenLoaded('category', fn (): ?array => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ] : null),
            'author' => [
                'name' => $this->author_name ?? ($this->relationLoaded('author') ? $this->author?->name : null),
                'title' => $this->author_title,
            ],
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'cover' => $this->getFirstMediaUrl('cover') ?: null,
            'reading_time' => $this->reading_time,
            'is_featured' => $this->is_featured,
            'tags' => $this->tags ?? [],
            'published_at' => $this->published_at?->toIso8601String(),
        ];
    }
}
