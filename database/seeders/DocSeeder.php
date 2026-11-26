<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Models\DocArticle;
use App\Models\DocSection;
use Illuminate\Database\Seeder;

/**
 * Sample docs structure so the frontend can start working on the portal.
 * TODO: replace the placeholder content with the real docs. Safe to re-run.
 */
class DocSeeder extends Seeder
{
    public function run(): void
    {
        $tree = [
            [
                'slug' => 'getting-started',
                'title' => 'البدء السريع',
                'icon' => 'rocket',
                'articles' => [
                    ['slug' => 'overview', 'title' => 'نظرة عامة على سيلزواي', 'excerpt' => 'تعرّف على المنظومة وأهم مكوناتها.'],
                    ['slug' => 'create-account', 'title' => 'إنشاء حساب الشركة', 'excerpt' => 'خطوات تفعيل حساب شركتك في أقل من دقيقة.'],
                ],
                'children' => [
                    [
                        'slug' => 'whatsapp',
                        'title' => 'ربط الواتساب',
                        'icon' => 'chat-bubble-left-right',
                        'articles' => [
                            ['slug' => 'connect-number', 'title' => 'ربط رقم الشركة', 'excerpt' => 'كيف تربط رقم الواتساب بالمنظومة.'],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($tree as $index => $section) {
            $this->seedSection($section, null, $index + 1);
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function seedSection(array $data, ?int $parentId, int $order): void
    {
        $section = DocSection::updateOrCreate(
            ['slug' => $data['slug']],
            [
                'parent_id' => $parentId,
                'title' => $data['title'],
                'icon' => $data['icon'] ?? null,
                'sort_order' => $order,
                'status' => ContentStatus::Published,
            ],
        );

        foreach ($data['articles'] ?? [] as $index => $article) {
            DocArticle::updateOrCreate(
                ['section_id' => $section->id, 'slug' => $article['slug']],
                [
                    'title' => $article['title'],
                    'excerpt' => $article['excerpt'],
                    'content' => "# {$article['title']}\n\n{$article['excerpt']}\n\n(TODO: المحتوى الحقيقي)",
                    'version' => '1.0',
                    'sort_order' => $index + 1,
                    'status' => ContentStatus::Published,
                    'published_at' => now(),
                ],
            );
        }

        foreach ($data['children'] ?? [] as $index => $child) {
            $this->seedSection($child, $section->id, $index + 1);
        }
    }
}
