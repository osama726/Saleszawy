<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Enums\BlockType;
use App\Models\Page;
use App\Models\PageBlock;
use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PartnerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_lists_published_partners_in_order_with_a_null_logo(): void
    {
        Partner::factory()->create(['name' => 'B', 'sort_order' => 2, 'url' => 'https://b.test']);
        Partner::factory()->create(['name' => 'A', 'sort_order' => 1]);
        Partner::factory()->draft()->create(['name' => 'Hidden', 'sort_order' => 3]);

        $this->getJson('/api/v1/partners')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.name', 'A')
            ->assertJsonPath('data.0.logo', null)
            ->assertJsonPath('data.1.name', 'B')
            ->assertJsonPath('data.1.url', 'https://b.test');
    }

    public function test_editing_a_partner_refreshes_the_cached_list(): void
    {
        $partner = Partner::factory()->create(['name' => 'Old']);

        $this->getJson('/api/v1/partners')->assertJsonPath('data.0.name', 'Old');

        $partner->update(['name' => 'New']);

        $this->getJson('/api/v1/partners')->assertJsonPath('data.0.name', 'New');
    }

    public function test_partners_block_endpoint_returns_the_heading_and_the_partners(): void
    {
        $page = Page::factory()->create(['slug' => 'home']);
        PageBlock::factory()->for($page)->create([
            'type' => BlockType::Partners,
            'data' => ['title' => 'شركات تعتمد على سيلزواي'],
        ]);
        Partner::factory()->create(['name' => 'P1', 'sort_order' => 1]);

        $this->getJson('/api/v1/pages/home/blocks/partners')
            ->assertOk()
            ->assertJsonPath('data.data.title', 'شركات تعتمد على سيلزواي')
            ->assertJsonCount(1, 'data.data.items')
            ->assertJsonPath('data.data.items.0.name', 'P1');
    }
}
