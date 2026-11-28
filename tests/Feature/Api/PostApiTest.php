<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Enums\BlockType;
use App\Models\Page;
use App\Models\PageBlock;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_lists_published_posts_with_featured_first_and_hides_drafts_or_scheduled(): void
    {
        $cat = PostCategory::factory()->create(['name' => 'أتمتة', 'slug' => 'automation']);

        Post::factory()->for($cat, 'category')->create([
            'title' => 'Older Post',
            'published_at' => now()->subDays(5),
        ]);
        Post::factory()->for($cat, 'category')->featured()->create([
            'title' => 'Featured Post',
            'published_at' => now()->subDays(10),
        ]);
        Post::factory()->for($cat, 'category')->draft()->create(['title' => 'Draft Post']);
        Post::factory()->for($cat, 'category')->scheduled()->create(['title' => 'Future Post']);

        $this->getJson('/api/v1/posts')
            ->assertOk()
            ->assertJsonCount(1, 'data.categories')
            ->assertJsonPath('data.categories.0.posts_count', 2)
            ->assertJsonCount(2, 'data.posts')
            ->assertJsonPath('data.posts.0.title', 'Featured Post')
            ->assertJsonPath('data.posts.1.title', 'Older Post');
    }

    public function test_filters_by_category_tag_featured_search_and_limit(): void
    {
        $crm = PostCategory::factory()->create(['slug' => 'crm']);
        $ai = PostCategory::factory()->create(['slug' => 'ai']);

        Post::factory()->for($crm, 'category')->featured()->create([
            'title' => 'CRM Guide',
            'tags' => ['واتساب', 'مبيعات'],
        ]);
        Post::factory()->for($ai, 'category')->create([
            'title' => 'AI Sales',
            'tags' => ['ذكاء اصطناعي'],
        ]);

        $this->getJson('/api/v1/posts?filter[category]=ai')
            ->assertOk()
            ->assertJsonCount(1, 'data.posts')
            ->assertJsonPath('data.posts.0.title', 'AI Sales');

        $this->getJson('/api/v1/posts?filter[tag]=واتساب')
            ->assertOk()
            ->assertJsonCount(1, 'data.posts')
            ->assertJsonPath('data.posts.0.title', 'CRM Guide');

        $this->getJson('/api/v1/posts?filter[featured]=1')
            ->assertOk()
            ->assertJsonCount(1, 'data.posts')
            ->assertJsonPath('data.posts.0.title', 'CRM Guide');

        $this->getJson('/api/v1/posts?q=AI')
            ->assertOk()
            ->assertJsonCount(1, 'data.posts')
            ->assertJsonPath('data.posts.0.title', 'AI Sales');

        $this->getJson('/api/v1/posts?limit=1')
            ->assertOk()
            ->assertJsonCount(1, 'data.posts');
    }

    public function test_shows_a_published_post_and_returns_404_for_draft_or_unknown(): void
    {
        $post = Post::factory()->create(['slug' => 'hello-sales', 'title' => 'Hello Sales', 'content' => 'Body']);
        Post::factory()->draft()->create(['slug' => 'draft-post']);

        $this->getJson('/api/v1/posts/hello-sales')
            ->assertOk()
            ->assertJsonPath('data.title', 'Hello Sales')
            ->assertJsonPath('data.content', 'Body');

        $this->getJson('/api/v1/posts/draft-post')->assertNotFound();
        $this->getJson('/api/v1/posts/missing')->assertNotFound();
    }

    public function test_editing_a_post_refreshes_the_cached_index_and_show_endpoints(): void
    {
        $post = Post::factory()->create(['slug' => 'cached-post', 'title' => 'Old Title']);

        $this->getJson('/api/v1/posts')->assertJsonPath('data.posts.0.title', 'Old Title');
        $this->getJson('/api/v1/posts/cached-post')->assertJsonPath('data.title', 'Old Title');

        $post->update(['title' => 'New Title']);

        $this->getJson('/api/v1/posts')->assertJsonPath('data.posts.0.title', 'New Title');
        $this->getJson('/api/v1/posts/cached-post')->assertJsonPath('data.title', 'New Title');
    }

    public function test_blog_block_endpoint_enriches_latest_posts(): void
    {
        $page = Page::factory()->create(['slug' => 'home']);
        PageBlock::factory()->for($page)->create([
            'type' => BlockType::Blog,
            'data' => ['title' => 'أحدث المقالات', 'limit' => 2],
        ]);

        Post::factory()->count(3)->create();

        $this->getJson('/api/v1/pages/home/blocks/blog')
            ->assertOk()
            ->assertJsonPath('data.key', 'blog')
            ->assertJsonCount(2, 'data.data.items');
    }
}
