<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Enums\BlockType;
use App\Models\Page;
use App\Models\PageBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PageBlockApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_a_single_block_by_its_key(): void
    {
        $page = Page::factory()->create(['slug' => 'home']);
        PageBlock::factory()->for($page)->create(['type' => BlockType::Faq, 'data' => ['title' => 'FAQ']]);
        PageBlock::factory()->for($page)->create(['type' => BlockType::Cta]);

        $this->getJson('/api/v1/pages/home/blocks/faq')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.key', 'faq')
            ->assertJsonPath('data.type', 'faq')
            ->assertJsonPath('data.data.title', 'FAQ');
    }

    public function test_hidden_and_unknown_blocks_are_not_found(): void
    {
        $page = Page::factory()->create(['slug' => 'home']);
        PageBlock::factory()->for($page)->create(['type' => BlockType::Faq, 'is_visible' => false]);

        $this->getJson('/api/v1/pages/home/blocks/faq')->assertNotFound()->assertJsonPath('code', 'NOT_FOUND');
        $this->getJson('/api/v1/pages/home/blocks/nope')->assertNotFound();
        $this->getJson('/api/v1/pages/missing/blocks/faq')->assertNotFound();
    }

    public function test_blocks_of_the_same_type_get_unique_keys(): void
    {
        $page = Page::factory()->create();

        $first = PageBlock::factory()->for($page)->create(['type' => BlockType::Cta]);
        $second = PageBlock::factory()->for($page)->create(['type' => BlockType::Cta]);
        $custom = PageBlock::factory()->for($page)->create(['type' => BlockType::Cta, 'key' => 'footer-cta']);

        $this->assertSame('cta', $first->key);
        $this->assertSame('cta-2', $second->key);
        $this->assertSame('footer-cta', $custom->key);
    }

    public function test_page_payload_exposes_the_block_keys(): void
    {
        $page = Page::factory()->create(['slug' => 'home']);
        PageBlock::factory()->for($page)->create(['type' => BlockType::Hero]);

        $this->getJson('/api/v1/pages/home')->assertJsonPath('data.blocks.0.key', 'hero');
    }
}
