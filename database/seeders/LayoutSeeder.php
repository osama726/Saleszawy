<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ButtonVariant;
use App\Enums\MenuItemKind;
use App\Enums\MenuLocation;
use App\Models\Menu;
use Illuminate\Database\Seeder;

/**
 * Seeds the navbar and footer menus of the current site.
 * Brand/footer texts are the defaults of the settings migrations (database/settings).
 * Footer anchors use "/#id" so they also work from pages other than home.
 */
class LayoutSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'key' => 'header-links',
                'title' => 'القائمة الرئيسية',
                'location' => MenuLocation::Header,
                'items' => [
                    ['label' => 'الرئيسية', 'url' => '/'],
                    ['label' => 'المميزات', 'url' => '/features'],
                    ['label' => 'الأسعار', 'url' => '/pricing'],
                    ['label' => 'التوثيق', 'url' => '/docs'],
                    ['label' => 'المدونة', 'url' => '/blog'],
                    ['label' => 'من نحن', 'url' => '/about'],
                ],
            ],
            [
                'key' => 'header-actions',
                'title' => 'أزرار الهيدر',
                'location' => MenuLocation::Header,
                'items' => [
                    ['label' => 'تسجيل الدخول', 'url' => '/login', 'kind' => MenuItemKind::Button, 'variant' => ButtonVariant::Ghost],
                    ['label' => 'ابدأ مجاناً', 'url' => '/register', 'kind' => MenuItemKind::Button, 'variant' => ButtonVariant::Primary],
                ],
            ],
            [
                'key' => 'footer-system',
                'title' => 'روابط المنظومة',
                'location' => MenuLocation::Footer,
                'items' => [
                    ['label' => 'عن سيلزواي', 'url' => '/#about'],
                    ['label' => 'المميزات والحلول', 'url' => '/#features'],
                    ['label' => 'ربط السنترال', 'url' => '/#pbx'],
                    ['label' => 'باقات الأسعار', 'url' => '/#pricing'],
                ],
            ],
            [
                'key' => 'footer-accounts',
                'title' => 'حسابات وبوابات',
                'location' => MenuLocation::Footer,
                'items' => [
                    ['label' => 'تسجيل دخول المشتركين', 'url' => '/login'],
                    ['label' => 'تسجيل شركة جديدة', 'url' => '/register'],
                    ['label' => 'الأسئلة الشائعة', 'url' => '/#faq'],
                ],
            ],
            [
                'key' => 'footer-trust',
                'title' => 'الأمان والاعتمادية',
                'location' => MenuLocation::Footer,
                'items' => [
                    ['label' => 'تشفير بيانات 256-bit SSL', 'kind' => MenuItemKind::Text],
                    ['label' => 'بنية سحابية معزولة Multi-Tenant', 'kind' => MenuItemKind::Text],
                    ['label' => 'Saleszawy Engine v2.0', 'kind' => MenuItemKind::Text],
                ],
            ],
        ];

        foreach ($menus as $index => $data) {
            $items = $data['items'];
            unset($data['items']);

            $menu = Menu::updateOrCreate(
                ['key' => $data['key']],
                $data + ['sort_order' => $index + 1, 'is_visible' => true],
            );

            $menu->items()->delete();

            foreach ($items as $position => $item) {
                $menu->items()->create($item + [
                    'kind' => MenuItemKind::Link,
                    'sort_order' => $position + 1,
                    'is_visible' => true,
                ]);
            }
        }
    }
}
