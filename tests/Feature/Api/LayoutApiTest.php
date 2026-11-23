<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Enums\ButtonVariant;
use App\Enums\MenuItemKind;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Settings\FooterSettings;
use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class LayoutApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_header_returns_ordered_links_buttons_and_hides_invisible_items(): void
    {
        $links = Menu::factory()->header()->create(['key' => 'header-links']);
        MenuItem::factory()->for($links)->create(['label' => 'B', 'sort_order' => 2]);
        MenuItem::factory()->for($links)->create(['label' => 'A', 'sort_order' => 1]);
        MenuItem::factory()->for($links)->create(['label' => 'Hidden', 'sort_order' => 3, 'is_visible' => false]);

        $actions = Menu::factory()->header()->create(['key' => 'header-actions']);
        MenuItem::factory()->for($actions)->create([
            'label' => 'ابدأ مجاناً',
            'kind' => MenuItemKind::Button,
            'variant' => ButtonVariant::Primary,
        ]);

        $this->getJson('/api/v1/layout/header')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data.links')
            ->assertJsonPath('data.links.0.label', 'A')
            ->assertJsonPath('data.links.1.label', 'B')
            ->assertJsonPath('data.actions.0.kind', 'button')
            ->assertJsonPath('data.actions.0.variant', 'primary')
            ->assertJsonPath('data.brand.text', 'سيلزواي');
    }

    public function test_menu_items_can_have_children(): void
    {
        $menu = Menu::factory()->header()->create(['key' => 'header-links']);
        $parent = MenuItem::factory()->for($menu)->create(['label' => 'Parent']);
        MenuItem::factory()->for($menu)->create(['label' => 'Child', 'parent_id' => $parent->id]);

        $this->getJson('/api/v1/layout/header')
            ->assertJsonCount(1, 'data.links')
            ->assertJsonPath('data.links.0.children.0.label', 'Child');
    }

    public function test_footer_returns_visible_footer_columns_only(): void
    {
        $system = Menu::factory()->create(['key' => 'footer-system', 'title' => 'روابط المنظومة', 'sort_order' => 1]);
        MenuItem::factory()->for($system)->create(['label' => 'عن سيلزواي']);
        Menu::factory()->create(['key' => 'footer-hidden', 'is_visible' => false, 'sort_order' => 2]);
        Menu::factory()->header()->create(['key' => 'header-links', 'sort_order' => 3]);

        $this->getJson('/api/v1/layout/footer')
            ->assertOk()
            ->assertJsonCount(1, 'data.columns')
            ->assertJsonPath('data.columns.0.key', 'footer-system')
            ->assertJsonPath('data.columns.0.title', 'روابط المنظومة')
            ->assertJsonPath('data.columns.0.items.0.label', 'عن سيلزواي');
    }

    public function test_footer_replaces_the_year_placeholder(): void
    {
        $response = $this->getJson('/api/v1/layout/footer')->assertOk();

        $this->assertStringContainsString((string) now()->year, $response->json('data.copyright'));
        $this->assertStringNotContainsString('{year}', $response->json('data.copyright'));
    }

    public function test_layout_returns_header_and_footer_together(): void
    {
        $this->getJson('/api/v1/layout')
            ->assertOk()
            ->assertJsonStructure(['data' => ['header' => ['brand', 'links', 'actions'], 'footer' => ['brand', 'columns', 'copyright']]]);
    }

    public function test_editing_a_menu_item_refreshes_the_cache(): void
    {
        $menu = Menu::factory()->header()->create(['key' => 'header-links']);
        $item = MenuItem::factory()->for($menu)->create(['label' => 'Old']);

        $this->getJson('/api/v1/layout/header')->assertJsonPath('data.links.0.label', 'Old');

        $item->update(['label' => 'New']);

        $this->getJson('/api/v1/layout/header')->assertJsonPath('data.links.0.label', 'New');
    }

    public function test_saving_settings_refreshes_the_cache(): void
    {
        $this->getJson('/api/v1/layout/footer')->assertOk();

        $footer = app(FooterSettings::class);
        $footer->description = 'وصف جديد';
        $footer->save();

        $general = app(GeneralSettings::class);
        $general->logo_text = 'اسم جديد';
        $general->save();

        $this->getJson('/api/v1/layout/footer')
            ->assertJsonPath('data.description', 'وصف جديد')
            ->assertJsonPath('data.brand.text', 'اسم جديد');
    }
}
