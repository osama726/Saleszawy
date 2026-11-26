<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\DocArticle;
use App\Models\DocSection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DocApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_tree_returns_nested_published_sections_with_their_articles(): void
    {
        $root = DocSection::factory()->create(['slug' => 'getting-started', 'sort_order' => 1]);
        $child = DocSection::factory()->create(['slug' => 'install', 'parent_id' => $root->id, 'sort_order' => 1]);
        DocSection::factory()->draft()->create(['slug' => 'hidden']);

        DocArticle::factory()->for($root, 'section')->create(['slug' => 'intro', 'sort_order' => 1]);
        DocArticle::factory()->for($root, 'section')->draft()->create(['slug' => 'wip']);
        DocArticle::factory()->for($child, 'section')->create(['slug' => 'requirements']);

        $this->getJson('/api/v1/docs/tree')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'getting-started')
            ->assertJsonCount(1, 'data.0.articles')
            ->assertJsonPath('data.0.articles.0.slug', 'intro')
            ->assertJsonPath('data.0.children.0.slug', 'install')
            ->assertJsonPath('data.0.children.0.articles.0.slug', 'requirements')
            ->assertJsonMissingPath('data.0.articles.0.content');
    }

    public function test_tree_orders_sections_and_articles_by_sort_order(): void
    {
        DocSection::factory()->create(['slug' => 'b', 'sort_order' => 2]);
        $a = DocSection::factory()->create(['slug' => 'a', 'sort_order' => 1]);
        DocArticle::factory()->for($a, 'section')->create(['slug' => 'second', 'sort_order' => 2]);
        DocArticle::factory()->for($a, 'section')->create(['slug' => 'first', 'sort_order' => 1]);

        $this->getJson('/api/v1/docs/tree')
            ->assertJsonPath('data.0.slug', 'a')
            ->assertJsonPath('data.1.slug', 'b')
            ->assertJsonPath('data.0.articles.0.slug', 'first')
            ->assertJsonPath('data.0.articles.1.slug', 'second');
    }

    public function test_children_of_an_unpublished_section_are_hidden(): void
    {
        $parent = DocSection::factory()->draft()->create();
        DocSection::factory()->create(['parent_id' => $parent->id]);

        $this->getJson('/api/v1/docs/tree')->assertOk()->assertJsonCount(0, 'data');
    }

    public function test_shows_a_published_article_with_its_section(): void
    {
        $section = DocSection::factory()->create(['slug' => 'guide', 'title' => 'الدليل']);
        DocArticle::factory()->for($section, 'section')->create([
            'slug' => 'intro',
            'title' => 'مقدمة',
            'content' => '# مقدمة',
        ]);

        $this->getJson('/api/v1/docs/guide/intro')
            ->assertOk()
            ->assertJsonPath('data.slug', 'intro')
            ->assertJsonPath('data.title', 'مقدمة')
            ->assertJsonPath('data.content', '# مقدمة')
            ->assertJsonPath('data.section.slug', 'guide')
            ->assertJsonPath('data.section.title', 'الدليل');
    }

    public function test_unpublished_scheduled_or_misplaced_articles_are_not_found(): void
    {
        $section = DocSection::factory()->create(['slug' => 'guide']);
        DocSection::factory()->create(['slug' => 'other']);
        $draftSection = DocSection::factory()->draft()->create(['slug' => 'draft-section']);

        DocArticle::factory()->for($section, 'section')->draft()->create(['slug' => 'draft']);
        DocArticle::factory()->for($section, 'section')->create(['slug' => 'later', 'published_at' => now()->addDay()]);
        DocArticle::factory()->for($section, 'section')->create(['slug' => 'ok']);
        DocArticle::factory()->for($draftSection, 'section')->create(['slug' => 'inside']);

        $this->getJson('/api/v1/docs/guide/draft')->assertNotFound()->assertJsonPath('code', 'NOT_FOUND');
        $this->getJson('/api/v1/docs/guide/later')->assertNotFound();
        $this->getJson('/api/v1/docs/other/ok')->assertNotFound();
        $this->getJson('/api/v1/docs/draft-section/inside')->assertNotFound();
        $this->getJson('/api/v1/docs/guide/missing')->assertNotFound();
    }

    public function test_editing_an_article_refreshes_the_cached_article_and_tree(): void
    {
        $section = DocSection::factory()->create(['slug' => 'guide']);
        $article = DocArticle::factory()->for($section, 'section')->create(['slug' => 'intro', 'title' => 'Old']);

        $this->getJson('/api/v1/docs/guide/intro')->assertJsonPath('data.title', 'Old');
        $this->getJson('/api/v1/docs/tree')->assertJsonPath('data.0.articles.0.title', 'Old');

        $article->update(['title' => 'New']);

        $this->getJson('/api/v1/docs/guide/intro')->assertJsonPath('data.title', 'New');
        $this->getJson('/api/v1/docs/tree')->assertJsonPath('data.0.articles.0.title', 'New');
    }

    public function test_renaming_an_article_slug_drops_the_old_address(): void
    {
        $section = DocSection::factory()->create(['slug' => 'guide']);
        $article = DocArticle::factory()->for($section, 'section')->create(['slug' => 'old']);

        $this->getJson('/api/v1/docs/guide/old')->assertOk();

        $article->update(['slug' => 'new']);

        $this->getJson('/api/v1/docs/guide/old')->assertNotFound();
        $this->getJson('/api/v1/docs/guide/new')->assertOk();
    }

    public function test_renaming_a_section_slug_moves_its_articles(): void
    {
        $section = DocSection::factory()->create(['slug' => 'old-section']);
        DocArticle::factory()->for($section, 'section')->create(['slug' => 'intro']);

        $this->getJson('/api/v1/docs/old-section/intro')->assertOk();

        $section->update(['slug' => 'new-section']);

        $this->getJson('/api/v1/docs/old-section/intro')->assertNotFound();
        $this->getJson('/api/v1/docs/new-section/intro')->assertOk()->assertJsonPath('data.section.slug', 'new-section');
    }

    public function test_deleting_an_article_removes_it_from_the_cached_endpoints(): void
    {
        $section = DocSection::factory()->create(['slug' => 'guide']);
        $article = DocArticle::factory()->for($section, 'section')->create(['slug' => 'intro']);

        $this->getJson('/api/v1/docs/guide/intro')->assertOk();
        $this->getJson('/api/v1/docs/tree')->assertJsonCount(1, 'data.0.articles');

        $article->delete();

        $this->getJson('/api/v1/docs/guide/intro')->assertNotFound();
        $this->getJson('/api/v1/docs/tree')->assertJsonCount(0, 'data.0.articles');
    }

    public function test_search_finds_published_articles_by_title_and_content(): void
    {
        $section = DocSection::factory()->create(['slug' => 'guide', 'title' => 'الدليل']);
        DocArticle::factory()->for($section, 'section')->create(['slug' => 'whatsapp', 'title' => 'ربط الواتساب', 'content' => 'خطوات']);
        DocArticle::factory()->for($section, 'section')->create(['slug' => 'other', 'title' => 'شيء آخر', 'content' => 'محتوى فيه كلمة الواتساب']);
        DocArticle::factory()->for($section, 'section')->draft()->create(['title' => 'الواتساب مخفي']);
        DocArticle::factory()->for($section, 'section')->create(['title' => 'لا علاقة', 'content' => 'نص عادي']);

        $this->getJson('/api/v1/docs/search?q=الواتساب')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.slug', 'whatsapp')
            ->assertJsonPath('data.0.section.slug', 'guide')
            ->assertJsonPath('meta.query', 'الواتساب');
    }

    public function test_search_requires_a_term_of_at_least_two_characters(): void
    {
        $this->getJson('/api/v1/docs/search')
            ->assertStatus(422)
            ->assertJsonPath('code', 'VALIDATION_ERROR');

        $this->getJson('/api/v1/docs/search?q=a')->assertStatus(422);
        $this->getJson('/api/v1/docs/search?q[]=x')->assertStatus(422);
    }
}
