<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\DocSection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A node of the docs tree (recursive through `children`).
 *
 * @mixin DocSection
 */
final class DocSectionResource extends JsonResource
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
            'icon' => $this->icon,
            'articles' => DocArticleSummaryResource::collection($this->whenLoaded('articles')),
            'children' => self::collection($this->whenLoaded('children')),
        ];
    }
}
