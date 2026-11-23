<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MenuLocation;
use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Menu>
 */
class MenuFactory extends Factory
{
    protected $model = Menu::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => fake()->unique()->slug(2),
            'title' => fake()->words(2, true),
            'location' => MenuLocation::Footer,
            'is_visible' => true,
        ];
    }

    public function header(): static
    {
        return $this->state(fn (): array => ['location' => MenuLocation::Header]);
    }
}
