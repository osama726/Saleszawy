<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Enums\BlockType;
use App\Models\Feature;
use App\Models\FeatureCategory;
use App\Models\Page;
use App\Models\PageBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class FeatureApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_lists_all_published_categories_and_features_in_order(): void
    {
        $homeCategory = FeatureCategory::factory()->create(['group' => 'home', 'name' => 'المبيعات', 'slug' => 'sales', 'sort_order' => 1]);
        $pageCategory = FeatureCategory::factory()->create(['group' => 'features', 'name' => 'الواتساب', 'slug' => 'whatsapp', 'sort_order' => 2]);

        Feature::factory()->for($homeCategory, 'category')->create([
            'group' => 'home',
            'title' => 'Home Feature B',
            'sort_order' => 2,
        ]);
        Feature::factory()->for($homeCategory, 'category')->create([
            'group' => 'home',
            'title' => 'Home Feature A',
            'sort_order' => 1,
        ]);
        Feature::factory()->for($pageCategory, 'category')->create([
            'group' => 'features',
            'title' => 'Page Feature',
            'badge' => 'بدون حد أقصى',
            'sort_order' => 3,
        ]);
        Feature::factory()->for($homeCategory, 'category')->draft()->create([
            'group' => 'home',
            'title' => 'Hidden Draft',
            'sort_order' => 4,
        ]);

        $this->getJson('/api/v1/features')
            ->assertOk()
            ->assertJsonCount(2, 'data.categories')
            ->assertJsonCount(3, 'data.features')
            ->assertJsonPath('data.features.0.title', 'Home Feature A')
            ->assertJsonPath('data.features.1.title', 'Home Feature B')
            ->assertJsonPath('data.features.2.title', 'Page Feature')
            ->assertJsonPath('data.features.2.badge', 'بدون حد أقصى');
    }

    public function test_filters_by_group_for_home_and_features_page(): void
    {
        $homeCategory = FeatureCategory::factory()->create(['group' => 'home', 'slug' => 'sales']);
        $pageCategory = FeatureCategory::factory()->create(['group' => 'features', 'slug' => 'pbx']);

        Feature::factory()->for($homeCategory, 'category')->create(['group' => 'home', 'title' => 'Home Item']);
        Feature::factory()->for($pageCategory, 'category')->create(['group' => 'features', 'title' => 'Page Item']);

        $this->getJson('/api/v1/features?filter[group]=home')
            ->assertOk()
            ->assertJsonCount(1, 'data.categories')
            ->assertJsonCount(1, 'data.features')
            ->assertJsonPath('data.categories.0.slug', 'sales')
            ->assertJsonPath('data.features.0.title', 'Home Item');

        $this->getJson('/api/v1/features?filter[group]=features')
            ->assertOk()
            ->assertJsonCount(1, 'data.categories')
            ->assertJsonCount(1, 'data.features')
            ->assertJsonPath('data.categories.0.slug', 'pbx')
            ->assertJsonPath('data.features.0.title', 'Page Item');
    }

    public function test_filters_by_category_slug(): void
    {
        $sales = FeatureCategory::factory()->create(['group' => 'home', 'slug' => 'sales']);
        $ai = FeatureCategory::factory()->create(['group' => 'home', 'slug' => 'ai']);

        Feature::factory()->for($sales, 'category')->create(['group' => 'home', 'title' => 'Sales Item']);
        Feature::factory()->for($ai, 'category')->create(['group' => 'home', 'title' => 'AI Item']);

        $this->getJson('/api/v1/features?filter[group]=home&filter[category]=ai')
            ->assertOk()
            ->assertJsonCount(1, 'data.features')
            ->assertJsonPath('data.features.0.title', 'AI Item');
    }

    public function test_rejects_an_invalid_group_filter(): void
    {
        $this->getJson('/api/v1/features?filter[group][]=x')
            ->assertStatus(422)
            ->assertJsonPath('code', 'VALIDATION_ERROR');
    }

    public function test_editing_a_feature_refreshes_the_cached_lists(): void
    {
        $category = FeatureCategory::factory()->create(['group' => 'home']);
        $feature = Feature::factory()->for($category, 'category')->create(['group' => 'home', 'title' => 'Old Title']);

        $this->getJson('/api/v1/features')->assertJsonPath('data.features.0.title', 'Old Title');
        $this->getJson('/api/v1/features?filter[group]=home')->assertJsonPath('data.features.0.title', 'Old Title');

        $feature->update(['title' => 'New Title']);

        $this->getJson('/api/v1/features')->assertJsonPath('data.features.0.title', 'New Title');
        $this->getJson('/api/v1/features?filter[group]=home')->assertJsonPath('data.features.0.title', 'New Title');
    }

    public function test_moving_a_feature_between_groups_updates_both_lists(): void
    {
        $category = FeatureCategory::factory()->create(['group' => 'home']);
        $feature = Feature::factory()->for($category, 'category')->create(['group' => 'home']);

        $this->getJson('/api/v1/features?filter[group]=home')->assertJsonCount(1, 'data.features');
        $this->getJson('/api/v1/features?filter[group]=features')->assertJsonCount(0, 'data.features');

        $feature->update(['group' => 'features']);

        $this->getJson('/api/v1/features?filter[group]=home')->assertJsonCount(0, 'data.features');
        $this->getJson('/api/v1/features?filter[group]=features')->assertJsonCount(1, 'data.features');
    }

    public function test_features_explorer_block_endpoint_enriches_home_features(): void
    {
        $page = Page::factory()->create(['slug' => 'home']);
        PageBlock::factory()->for($page)->create([
            'type' => BlockType::FeaturesExplorer,
            'data' => ['title' => 'مستكشف القدرات', 'group' => 'home'],
        ]);

        $homeCategory = FeatureCategory::factory()->create(['group' => 'home', 'slug' => 'sales']);
        $pageCategory = FeatureCategory::factory()->create(['group' => 'features', 'slug' => 'pbx']);

        Feature::factory()->for($homeCategory, 'category')->create(['group' => 'home', 'title' => 'Home Feature']);
        Feature::factory()->for($pageCategory, 'category')->create(['group' => 'features', 'title' => 'Page Feature']);

        $this->getJson('/api/v1/pages/home/blocks/features_explorer')
            ->assertOk()
            ->assertJsonPath('data.key', 'features_explorer')
            ->assertJsonCount(1, 'data.data.categories')
            ->assertJsonCount(1, 'data.data.items')
            ->assertJsonPath('data.data.items.0.title', 'Home Feature');
    }

    public function test_the_page_endpoint_does_not_embed_features(): void
    {
        $page = Page::factory()->create(['slug' => 'home']);
        PageBlock::factory()->for($page)->create([
            'type' => BlockType::FeaturesExplorer,
            'data' => ['title' => 'مستكشف القدرات', 'group' => 'home'],
        ]);
        $category = FeatureCategory::factory()->create(['group' => 'home']);
        Feature::factory()->for($category, 'category')->create(['group' => 'home']);

        $this->getJson('/api/v1/pages/home')
            ->assertOk()
            ->assertJsonMissingPath('data.blocks.0.data.items');
    }
}
