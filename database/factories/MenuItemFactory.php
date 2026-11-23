<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MenuItemKind;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MenuItem>
 */
class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'menu_id' => Menu::factory(),
            'label' => fake()->words(2, true),
            'url' => '/' . fake()->slug(1),
            'kind' => MenuItemKind::Link,
            'opens_in_new_tab' => false,
            'is_visible' => true,
        ];
    }
}
