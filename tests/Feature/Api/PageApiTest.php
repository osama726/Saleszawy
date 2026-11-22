<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Enums\BlockType;
use App\Models\Page;
use App\Models\PageBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PageApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_published_page_with_visible_blocks_in_order(): void
    {
        $page = Page::factory()->create(['slug' => 'home', 'title' => 'الرئيسية']);

        PageBlock::factory()->for($page)->create(['type' => BlockType::Cta, 'sort_order' => 2, 'data' => ['title' => 'B']]);
        PageBlock::factory()->for($page)->create(['type' => BlockType::Hero, 'sort_order' => 1, 'data' => ['title' => 'A']]);
        PageBlock::factory()->for($page)->create(['type' => BlockType::Faq, 'sort_order' => 3, 'is_visible' => false]);

        $this->getJson('/api/v1/pages/home')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.slug', 'home')
            ->assertJsonCount(2, 'data.blocks')
            ->assertJsonPath('data.blocks.0.type', 'hero')
            ->assertJsonPath('data.blocks.0.data.title', 'A')
            ->assertJsonPath('data.blocks.1.type', 'cta');
    }

    public function test_draft_page_is_not_found(): void
    {
        Page::factory()->draft()->create(['slug' => 'secret']);

        $this->getJson('/api/v1/pages/secret')
            ->assertNotFound()
            ->assertJsonPath('success', false)
            ->assertJsonPath('code', 'NOT_FOUND');
    }

    public function test_unknown_page_is_not_found(): void
    {
        $this->getJson('/api/v1/pages/nope')
            ->assertNotFound()
            ->assertJsonPath('code', 'NOT_FOUND');
    }

    public function test_editing_a_block_refreshes_the_cached_page(): void
    {
        $page = Page::factory()->create(['slug' => 'home']);
        $block = PageBlock::factory()->for($page)->create(['type' => BlockType::Hero, 'data' => ['title' => 'Old']]);

        $this->getJson('/api/v1/pages/home')->assertJsonPath('data.blocks.0.data.title', 'Old');

        $block->update(['data' => ['title' => 'New']]);

        $this->getJson('/api/v1/pages/home')->assertJsonPath('data.blocks.0.data.title', 'New');
    }
}
