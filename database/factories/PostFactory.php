<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ContentStatus;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(5);

        return [
            'category_id' => PostCategory::factory(),
            'author_id' => null,
            'author_name' => 'فريق سيلزواي',
            'author_title' => 'مستشارو المبيعات والتحول الرقمي',
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(1, 9999),
            'excerpt' => fake()->sentence(12),
            'content' => fake()->paragraphs(3, true),
            'reading_time' => fake()->numberBetween(3, 10),
            'is_featured' => false,
            'tags' => ['السيلزواي', 'المبيعات B2B'],
            'seo_title' => null,
            'seo_description' => null,
            'status' => ContentStatus::Published,
            'published_at' => now()->subDay(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => ['status' => ContentStatus::Draft]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (): array => ['published_at' => now()->addDay()]);
    }

    public function featured(): static
    {
        return $this->state(fn (): array => ['is_featured' => true]);
    }
}
