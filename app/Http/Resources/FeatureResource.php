<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Feature
 */
final class FeatureResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'group' => $this->group,
            'category' => $this->whenLoaded('category', fn (): array => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'badge' => $this->badge,
            'description' => $this->description,
            'points' => $this->points ?? [],
            'how_it_works' => $this->how_it_works ?? [],
            'icon' => $this->icon,
            'cta' => [
                'label' => $this->cta_label,
                'url' => $this->cta_url,
            ],
        ];
    }
}
