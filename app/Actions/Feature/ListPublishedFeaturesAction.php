<?php

declare(strict_types=1);

namespace App\Actions\Feature;

use App\Models\Feature;
use App\Models\FeatureCategory;
use Illuminate\Database\Eloquent\Collection;

final class ListPublishedFeaturesAction
{
    /**
     * @return array{categories: Collection<int, FeatureCategory>, features: Collection<int, Feature>}
     */
    public function execute(?string $group = null): array
    {
        $categories = FeatureCategory::query()
            ->when($group !== null, static fn ($query) => $query->where('group', $group))
            ->withCount([
                'features' => static fn ($query) => $query
                    ->published()
                    ->when($group !== null, static fn ($q) => $q->where('group', $group)),
            ])
            ->ordered()
            ->get();

        $features = Feature::query()
            ->with('category')
            ->published()
            ->when($group !== null, static fn ($query) => $query->where('group', $group))
            ->ordered()
            ->get();

        return [
            'categories' => $categories,
            'features' => $features,
        ];
    }
}
