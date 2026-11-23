<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ContentStatus;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    protected $model = Plan::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'slug' => fake()->unique()->slug(2),
            'description' => fake()->sentence(),
            'price_monthly' => 1000,
            'price_yearly' => 9600,
            'currency' => 'EGP',
            'trial_days' => 0,
            'max_sales_users' => 5,
            'max_leads' => 10000,
            'features' => ['ميزة أولى', 'ميزة ثانية'],
            'is_featured' => false,
            'badge' => null,
            'cta_label' => 'اشترك الآن',
            'cta_url' => '/register',
            'status' => ContentStatus::Published,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => ['status' => ContentStatus::Draft]);
    }
}
