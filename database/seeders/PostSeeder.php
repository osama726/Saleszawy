<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BlockType;
use App\Enums\ContentStatus;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Database\Seeder;

/**
 * Seeds the "blog" page, post categories, and initial blog articles.
 * Safe to re-run.
 */
class PostSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedBlogPage();
        $categoryIds = $this->seedCategories();
        $this->seedPosts($categoryIds);
    }

    private function seedBlogPage(): void
    {
        $page = Page::updateOrCreate(
            ['slug' => 'blog'],
            [
                'title' => 'المدونة',
                'seo_title' => 'مدونة سيلزواي للمبيعات الحديثة | Saleszawy',
                'seo_description' => 'أحدث المقالات وأسرار النجاح، ومؤشرات أتمتة المبيعات والذكاء الاصطناعي لمساعدة فريقك على تحقيق مبيعات لا تتوقف.',
                'status' => ContentStatus::Published,
                'published_at' => now(),
            ],
        );

        $page->blocks()->delete();

        $blocks = [
            [
                'type' => BlockType::Hero,
                'data' => [
                    'eyebrow' => 'بنك المعرفة واستراتيجيات المبيعات',
                    'title' => 'مدونة سيلزواي للمبيعات الحديثة',
                    'subtitle' => 'أحدث المقالات وأسرار النجاح، ومؤشرات أتمتة المبيعات والذكاء الاصطناعي لمساعدة فريقك على تحقيق مبيعات لا تتوقف.',
                ],
            ],
            [
                'type' => BlockType::Cta,
                'data' => [
                    'title' => 'جرب سيلزواي مجاناً',
                    'subtitle' => 'ضاعف مبيعات شركتك مع نظام المبيعات CRM ومساعد الواتساب الذكي المتكامل.',
                    'primary_cta' => ['label' => 'ابدأ تجربتك المجانية (3 أيام)', 'url' => '/register'],
                ],
            ],
        ];

        foreach ($blocks as $index => $block) {
            $page->blocks()->create([
                'type' => $block['type'],
                'data' => $block['data'],
                'sort_order' => $index + 1,
                'is_visible' => true,
            ]);
        }
    }

    /**
     * @return array<string, int>
     */
    private function seedCategories(): array
    {
        $categories = [
            'sales-automation-crm' => 'أتمتة المبيعات والـ CRM',
            'whatsapp-persuasion-trust' => 'واتساب ومهارة الإقناع وبناء الثقة',
            'b2b-lead-scraper' => 'صائد العملاء وموسوعة B2B',
            'deal-rooms-ai' => 'غرف الصفقات والذكاء الاصطناعي',
            'sales-team-strategies' => 'استراتيجيات وإدارة فرق البيع',
        ];

        $ids = [];
        $order = 1;
        foreach ($categories as $slug => $name) {
            $category = PostCategory::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'sort_order' => $order++],
            );
            $ids[$slug] = $category->id;
        }

        return $ids;
    }

    /**
     * @param  array<string, int>  $categoryIds
     */
    private function seedPosts(array $categoryIds): void
    {
        $posts = [
            [
                'category' => 'sales-automation-crm',
                'slug' => 'b2b-whatsapp-sales-guide-2026',
                'title' => 'دليلك الشامل لزيادة مبيعات B2B عبر الواتساب في 2026 دون التعرض للحظر',
                'excerpt' => 'تعرّف على الخطوات الذهبية لتهيئة رقم شركتك، إرسال الحملات المصغرة باحترافية، واستخدام الذكاء الاصطناعي في تحويل المحادثات إلى صفقات مغلقة.',
                'content' => "## مقدمة\n\nأصبح الواتساب القناة الأولى للتواصل التجاري في السوق العربي.\n\n### أهم الخطوات\n- تهيئة رقم الشركة وتوثيق الهوية.\n- استخدام الردود الذكية وتوزيع المحادثات على الفريق.\n- متابعة الصفقات داخل مسار CRM واضح.",
                'reading_time' => 6,
                'is_featured' => true,
                'author_name' => 'فريق استشارات سيلزواي',
                'author_title' => 'مستشارو التحول الرقمي وأتمتة المبيعات',
                'tags' => ['السيلزواي', 'المبيعات B2B', 'أتمتة CRM', 'اتصال الواتساب'],
                'published_at' => '2026-05-15 10:00:00',
            ],
            [
                'category' => 'whatsapp-persuasion-trust',
                'slug' => 'connect-one-whatsapp-number-to-multiple-agents',
                'title' => 'كيف تربط رقم واتساب واحد بأكثر من موظف؟',
                'excerpt' => 'يمكنك ربط رقم واتساب واحد بأكثر من موظف بسهولة، وتوزيع المحادثات تلقائياً بين الفريق مع متابعة كاملة من لوحة تحكم سيلزواي الموحدة.',
                'content' => "## صندوق وارد موحد لفريق المبيعات\n\nبدلاً من تشتت المحادثات على هواتف شخصية، يمكنك ربط رقم الشركة بصندوق وارد مشترك يمنح كل مندوب صلاحياته الخاصة.",
                'reading_time' => 4,
                'is_featured' => false,
                'author_name' => 'فريق سيلزواي',
                'author_title' => null,
                'tags' => ['اتصال الواتساب', 'إدارة فرق المبيعات'],
                'published_at' => '2026-05-10 10:00:00',
            ],
            [
                'category' => 'whatsapp-persuasion-trust',
                'slug' => 'anti-ban-system-safe-messaging',
                'title' => 'نظام مكافحة الحظر (Anti-Ban) الاحترافي: كيف ترسل آلاف الرسائل بأمان تام؟',
                'excerpt' => 'حلول تقنية متقدمة تحمي أرقام شركتك من الحظر عبر تنظيم فترات الإرسال وتخصيص المحتوى الذكي.',
                'content' => "## حماية أرقام التواصل\n\nتنظيم الإرسال وتجنب الرسائل المكررة يحافظ على سمعة رقم شركتك ويزيد من معدل التفاعل.",
                'reading_time' => 5,
                'is_featured' => false,
                'author_name' => 'فريق هندسة الأنظمة في سيلزواي',
                'author_title' => null,
                'tags' => ['اتصال الواتساب', 'أتمتة CRM'],
                'published_at' => '2026-05-05 10:00:00',
            ],
            [
                'category' => 'b2b-lead-scraper',
                'slug' => 'b2b-scraper-extract-5000-leads-daily',
                'title' => 'ما أداة الشركات B2B Scraper؟ وكيف تستخرج 5,000 عميل محتمل يومياً من قوقل ماب؟',
                'excerpt' => 'دليل عملي لاستخراج بيانات الشركات المستهدفة وبناء قاعدة عملاء محتملين دقيقة ومحدثة لفريق المبيعات.',
                'content' => "## صائد الشركات B2B\n\nاختر القطاع والمدينة المستهدفة، واستخرج بيانات التواصل مباشرة إلى مسار المبيعات في سيلزواي.",
                'reading_time' => 8,
                'is_featured' => false,
                'author_name' => 'فريق سيلزواي',
                'author_title' => null,
                'tags' => ['المبيعات B2B', 'صائد الشركات'],
                'published_at' => '2026-04-28 10:00:00',
            ],
            [
                'category' => 'deal-rooms-ai',
                'slug' => 'ai-deal-rooms-close-deals-in-48-hours',
                'title' => 'غرف الصفقات التفاعلية (AI Deal Rooms): سر إغلاق الصفقات الكبرى في 48 ساعة',
                'excerpt' => 'اجعل العميل يتفاعل مع عرض السعر والعقد في غرفة رقمية واحدة مع تتبع فوري وتوقيع إلكتروني.',
                'content' => "## غرف الصفقات الذكية\n\nتجمع غرفة الصفقة كل ما يحتاجه العميل لاتخاذ القرار سريعاً دون تبادل عشرات الملفات المتفرقة.",
                'reading_time' => 6,
                'is_featured' => false,
                'author_name' => 'فريق سيلزواي',
                'author_title' => null,
                'tags' => ['الذكاء الاصطناعي', 'إغلاق الصفقات'],
                'published_at' => '2026-04-20 10:00:00',
            ],
            [
                'category' => 'sales-team-strategies',
                'slug' => 'why-traditional-crm-fails-arab-sales-teams',
                'title' => 'لماذا تفشل أنظمة الـ CRM التقليدية في إرضاء مدراء المبيعات بالشركات العربية؟',
                'excerpt' => 'مقارنة بين الأنظمة التقليدية المعقدة ومنظومة سيلزواي المصممة لسرعة فرق المبيعات اليومية عبر الواتساب.',
                'content' => "## التحدي الحقيقي\n\nالأنظمة التقليدية تفصل بين المحادثة اليومية وبيانات العميل، بينما يدمج سيلزواي الاثنين في شاشة واحدة.",
                'reading_time' => 7,
                'is_featured' => false,
                'author_name' => 'فريق سيلزواي',
                'author_title' => null,
                'tags' => ['إدارة فرق المبيعات', 'أتمتة CRM', 'التقارير والأداء'],
                'published_at' => '2026-04-14 10:00:00',
            ],
        ];

        foreach ($posts as $item) {
            Post::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'category_id' => $categoryIds[$item['category']],
                    'author_name' => $item['author_name'],
                    'author_title' => $item['author_title'],
                    'title' => $item['title'],
                    'excerpt' => $item['excerpt'],
                    'content' => $item['content'],
                    'reading_time' => $item['reading_time'],
                    'is_featured' => $item['is_featured'],
                    'tags' => $item['tags'],
                    'status' => ContentStatus::Published,
                    'published_at' => $item['published_at'],
                ],
            );
        }
    }
}
