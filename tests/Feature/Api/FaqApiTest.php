<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Enums\BlockType;
use App\Models\Faq;
use App\Models\Page;
use App\Models\PageBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class FaqApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_lists_published_faqs_in_order(): void
    {
        Faq::factory()->create(['question' => 'B', 'sort_order' => 2]);
        Faq::factory()->create(['question' => 'A', 'sort_order' => 1]);
        Faq::factory()->draft()->create(['question' => 'Hidden', 'sort_order' => 3]);

        $this->getJson('/api/v1/faqs')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.question', 'A')
            ->assertJsonPath('data.1.question', 'B');
    }

    public function test_filters_by_group(): void
    {
        Faq::factory()->create(['group' => 'home', 'question' => 'Home Q']);
        Faq::factory()->create(['group' => 'pricing', 'question' => 'Pricing Q']);

        $response = $this->getJson('/api/v1/faqs?filter[group]=pricing');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.question', 'Pricing Q');
    }

    public function test_rejects_an_invalid_group_filter(): void
    {
        $this->getJson('/api/v1/faqs?filter[group][]=x')
            ->assertStatus(422)
            ->assertJsonPath('code', 'VALIDATION_ERROR');
    }

    public function test_editing_a_faq_refreshes_the_cached_lists(): void
    {
        $faq = Faq::factory()->create(['group' => 'home', 'question' => 'Old']);

        $this->getJson('/api/v1/faqs')->assertJsonPath('data.0.question', 'Old');
        $this->getJson('/api/v1/faqs?filter[group]=home')->assertJsonPath('data.0.question', 'Old');

        $faq->update(['question' => 'New']);

        $this->getJson('/api/v1/faqs')->assertJsonPath('data.0.question', 'New');
        $this->getJson('/api/v1/faqs?filter[group]=home')->assertJsonPath('data.0.question', 'New');
    }

    public function test_moving_a_faq_between_groups_updates_both_lists(): void
    {
        $faq = Faq::factory()->create(['group' => 'home']);

        $this->getJson('/api/v1/faqs?filter[group]=home')->assertJsonCount(1, 'data');
        $this->getJson('/api/v1/faqs?filter[group]=pricing')->assertJsonCount(0, 'data');

        $faq->update(['group' => 'pricing']);

        $this->getJson('/api/v1/faqs?filter[group]=home')->assertJsonCount(0, 'data');
        $this->getJson('/api/v1/faqs?filter[group]=pricing')->assertJsonCount(1, 'data');
    }

    public function test_faq_block_endpoint_returns_the_heading_and_the_questions(): void
    {
        $page = Page::factory()->create(['slug' => 'home']);
        PageBlock::factory()->for($page)->create([
            'type' => BlockType::Faq,
            'data' => ['title' => 'كل ما تود معرفته', 'group' => 'home'],
        ]);

        Faq::factory()->create(['group' => 'home', 'question' => 'Q1', 'sort_order' => 1]);
        Faq::factory()->create(['group' => 'pricing', 'question' => 'Other group']);
        Faq::factory()->draft()->create(['group' => 'home', 'question' => 'Draft']);

        $this->getJson('/api/v1/pages/home/blocks/faq')
            ->assertOk()
            ->assertJsonPath('data.key', 'faq')
            ->assertJsonPath('data.data.title', 'كل ما تود معرفته')
            ->assertJsonCount(1, 'data.data.items')
            ->assertJsonPath('data.data.items.0.question', 'Q1');
    }

    public function test_faq_block_reflects_faq_changes_without_touching_the_page_cache(): void
    {
        $page = Page::factory()->create(['slug' => 'home']);
        PageBlock::factory()->for($page)->create(['type' => BlockType::Faq, 'data' => ['title' => 'T', 'group' => 'home']]);
        $faq = Faq::factory()->create(['group' => 'home', 'question' => 'Old']);

        $this->getJson('/api/v1/pages/home/blocks/faq')
            ->assertOk()
            ->assertJsonPath('data.data.items.0.question', 'Old');

        $faq->update(['question' => 'New']);

        $this->getJson('/api/v1/pages/home/blocks/faq')->assertJsonPath('data.data.items.0.question', 'New');
    }

    public function test_the_page_endpoint_does_not_embed_the_questions(): void
    {
        $page = Page::factory()->create(['slug' => 'home']);
        PageBlock::factory()->for($page)->create(['type' => BlockType::Faq, 'data' => ['title' => 'T', 'group' => 'home']]);
        Faq::factory()->create(['group' => 'home']);

        $this->getJson('/api/v1/pages/home')->assertOk()->assertJsonMissingPath('data.blocks.0.data.items');
    }
}
