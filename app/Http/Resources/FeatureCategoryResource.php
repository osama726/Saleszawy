<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\FeatureCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin FeatureCategory
 */
final class FeatureCategoryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'group' => $this->group,
            'name' => $this->name,
            'slug' => $this->slug,
            'features_count' => $this->whenCounted('features'),
        ];
    }
}
