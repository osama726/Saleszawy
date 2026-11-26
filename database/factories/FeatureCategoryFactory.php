<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\FeatureCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<FeatureCategory>
 */
class FeatureCategoryFactory extends Factory
{
    protected $model = FeatureCategory::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 9999),
            'group' => 'home',
            'sort_order' => 1,
        ];
    }
}
