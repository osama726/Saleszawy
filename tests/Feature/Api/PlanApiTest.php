<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PlanApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_lists_published_plans_in_order(): void
    {
        Plan::factory()->create(['name' => 'B', 'sort_order' => 2, 'price_monthly' => 6500, 'price_yearly' => 62400]);
        Plan::factory()->create(['name' => 'A', 'sort_order' => 1, 'price_monthly' => 0, 'price_yearly' => 0]);
        Plan::factory()->draft()->create(['name' => 'Hidden', 'sort_order' => 3]);

        $this->getJson('/api/v1/plans')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.name', 'A')
            ->assertJsonPath('data.1.name', 'B')
            ->assertJsonPath('data.1.price.monthly', 6500)
            ->assertJsonPath('data.1.price.yearly', 62400)
            ->assertJsonPath('data.1.price.currency', 'EGP');
    }

    public function test_plan_shape_has_limits_features_and_cta(): void
    {
        Plan::factory()->create([
            'max_sales_users' => 5,
            'max_leads' => 40000,
            'features' => ['x', 'y'],
            'is_featured' => true,
            'badge' => 'الأكثر طلباً',
            'cta_label' => 'اشترك',
            'cta_url' => '/register?plan=2',
        ]);

        $this->getJson('/api/v1/plans')
            ->assertJsonPath('data.0.limits.sales_users', 5)
            ->assertJsonPath('data.0.limits.leads', 40000)
            ->assertJsonPath('data.0.features', ['x', 'y'])
            ->assertJsonPath('data.0.is_featured', true)
            ->assertJsonPath('data.0.badge', 'الأكثر طلباً')
            ->assertJsonPath('data.0.cta.url', '/register?plan=2');
    }

    public function test_editing_a_plan_refreshes_the_cached_list(): void
    {
        $plan = Plan::factory()->create(['name' => 'Old']);

        $this->getJson('/api/v1/plans')->assertJsonPath('data.0.name', 'Old');

        $plan->update(['name' => 'New']);

        $this->getJson('/api/v1/plans')->assertJsonPath('data.0.name', 'New');
    }

    public function test_new_plans_are_appended_to_the_end(): void
    {
        $first = Plan::factory()->create();
        $second = Plan::factory()->create();

        $this->assertSame(1, $first->sort_order);
        $this->assertSame(2, $second->sort_order);
    }
}
