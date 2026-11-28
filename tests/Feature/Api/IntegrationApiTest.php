<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Enums\BlockType;
use App\Models\Integration;
use App\Models\Page;
use App\Models\PageBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class IntegrationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_lists_published_integrations_in_order_with_tags(): void
    {
        Integration::factory()->create(['title' => 'B', 'sort_order' => 2, 'tags' => ['x', 'y']]);
        Integration::factory()->create(['title' => 'A', 'sort_order' => 1, 'icon' => 'calculator']);
        Integration::factory()->draft()->create(['title' => 'Hidden', 'sort_order' => 3]);

        $this->getJson('/api/v1/integrations')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.title', 'A')
            ->assertJsonPath('data.0.icon', 'calculator')
            ->assertJsonPath('data.1.title', 'B')
            ->assertJsonPath('data.1.tags', ['x', 'y']);
    }

    public function test_editing_an_integration_refreshes_the_cached_list(): void
    {
        $integration = Integration::factory()->create(['title' => 'Old']);

        $this->getJson('/api/v1/integrations')->assertJsonPath('data.0.title', 'Old');

        $integration->update(['title' => 'New']);

        $this->getJson('/api/v1/integrations')->assertJsonPath('data.0.title', 'New');
    }

    public function test_integrations_block_endpoint_returns_the_heading_and_the_cards(): void
    {
        $page = Page::factory()->create(['slug' => 'home']);
        PageBlock::factory()->for($page)->create([
            'type' => BlockType::Integrations,
            'data' => ['title' => 'تكاملات وحلول متكاملة'],
        ]);
        Integration::factory()->create(['title' => 'I1', 'sort_order' => 1]);

        $this->getJson('/api/v1/pages/home/blocks/integrations')
            ->assertOk()
            ->assertJsonPath('data.data.title', 'تكاملات وحلول متكاملة')
            ->assertJsonCount(1, 'data.data.items')
            ->assertJsonPath('data.data.items.0.title', 'I1');
    }
}
