<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ContentStatus;
use App\Models\DocSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocSection>
 */
class DocSectionFactory extends Factory
{
    protected $model = DocSection::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'parent_id' => null,
            'title' => fake()->words(2, true),
            'slug' => fake()->unique()->slug(2),
            'icon' => null,
            'status' => ContentStatus::Published,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => ['status' => ContentStatus::Draft]);
    }
}
