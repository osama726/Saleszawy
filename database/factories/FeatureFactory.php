<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ContentStatus;
use App\Models\Feature;
use App\Models\FeatureCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Feature>
 */
class FeatureFactory extends Factory
{
    protected $model = Feature::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'category_id' => FeatureCategory::factory(),
            'group' => 'home',
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(1, 9999),
            'summary' => fake()->sentence(6),
            'badge' => null,
            'description' => fake()->paragraph(),
            'points' => [fake()->sentence(4), fake()->sentence(4)],
            'how_it_works' => [
                ['title' => fake()->word(), 'icon' => 'message-circle'],
                ['title' => fake()->word(), 'icon' => 'zap'],
            ],
            'icon' => 'sparkles',
            'cta_label' => null,
            'cta_url' => null,
            'sort_order' => 1,
            'status' => ContentStatus::Published,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => ['status' => ContentStatus::Draft]);
    }
}
