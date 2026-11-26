<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\DocArticle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin DocArticle
 */
final class DocArticleResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'version' => $this->version,
            'section' => $this->whenLoaded('section', fn (): array => [
                'id' => $this->section->id,
                'title' => $this->section->title,
                'slug' => $this->section->slug,
            ]),
            'published_at' => $this->published_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
