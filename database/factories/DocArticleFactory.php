<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ContentStatus;
use App\Models\DocArticle;
use App\Models\DocSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocArticle>
 */
class DocArticleFactory extends Factory
{
    protected $model = DocArticle::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'section_id' => DocSection::factory(),
            'title' => fake()->sentence(3),
            'slug' => fake()->unique()->slug(2),
            'excerpt' => fake()->sentence(),
            'content' => implode("\n\n", fake()->paragraphs(3)),
            'version' => '1.0',
            'status' => ContentStatus::Published,
            'published_at' => now()->subDay(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => [
            'status' => ContentStatus::Draft,
            'published_at' => null,
        ]);
    }
}
