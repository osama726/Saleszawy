<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Page
 */
final class PageResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'seo' => [
                'title' => $this->seo_title ?? $this->title,
                'description' => $this->seo_description,
            ],
            'published_at' => $this->published_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'blocks' => PageBlockResource::collection($this->whenLoaded('visibleBlocks')),
        ];
    }
}
