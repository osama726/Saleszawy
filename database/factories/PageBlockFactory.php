<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\BlockType;
use App\Models\Page;
use App\Models\PageBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PageBlock>
 */
class PageBlockFactory extends Factory
{
    protected $model = PageBlock::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'page_id' => Page::factory(),
            'type' => BlockType::Cta,
            'data' => ['title' => fake()->sentence(4)],
            'is_visible' => true,
        ];
    }
}
