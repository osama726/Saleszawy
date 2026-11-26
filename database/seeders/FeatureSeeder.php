<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BlockType;
use App\Enums\ContentStatus;
use App\Models\Feature;
use App\Models\FeatureCategory;
use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Seeds both the Home "Features Explorer" section (group: home)
 * and the Features page (group: features + Page slug: features).
 * Safe to re-run.
 */
class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedFeaturesPage();
        $this->seedHomeFeatures();
        $this->seedPageFeatures();
    }

    private function seedFeaturesPage(): void
    {
        $page = Page::updateOrCreate(
            ['slug' => 'features'],
            [
                'title' => 'المميزات',
                'seo_title' => 'المميزات | سيلزواي Saleszawy',
                'seo_description' => 'كل ما تحتاجه لمضاعفة مبيعاتك في منصة واحدة: إدارة الواتساب الموحدة، المساعد الذكي Sell AI، السنترال السحابي، وخطوط أنابيب المبيعات.',
                'status' => ContentStatus::Published,
                'published_at' => now(),
            ],
        );

        $page->blocks()->delete();

        $blocks = [
            [
                'type' => BlockType::Hero,
                'data' => [
                    'eyebrow' => 'إمكانيات منظومة سيلزواي',
                    'title' => 'كل ما تحتاجه لمضاعفة مبيعاتك في منصة واحدة',
                    'subtitle' => 'ودّع تشتت التطبيقات المنفصلة. امنح فريق مبيعاتك الأدوات الفائقة لإدارة العملاء، الرد الآلي، وتحويل المحادثات إلى أرباح حقيقية.',
                ],
            ],
            [
                'type' => BlockType::Cta,
                'data' => [
                    'title' => 'جاهز لنقل مبيعاتك إلى المستوى التالي؟',
                    'subtitle' => 'ابدأ الآن تجربتك المجانية واكتشف كيف يغير سيلزواي طريقة عمل فريقك بالكامل.',
                    'cta' => ['label' => 'احجز استشارتك مجانية الآن', 'url' => '/contact'],
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

    private function seedHomeFeatures(): void
    {
        $categories = [
            'sales' => ['name' => 'المبيعات', 'sort_order' => 1],
            'communication' => ['name' => 'التواصل', 'sort_order' => 2],
            'ai' => ['name' => 'الذكاء الاصطناعي', 'sort_order' => 3],
            'admin-security' => ['name' => 'الإدارة والأمان', 'sort_order' => 4],
        ];

        $categoryIds = [];
        foreach ($categories as $slug => $data) {
            $category = FeatureCategory::updateOrCreate(
                ['slug' => $slug],
                ['name' => $data['name'], 'group' => 'home', 'sort_order' => $data['sort_order']],
            );
            $categoryIds[$slug] = $category->id;
        }

        $features = [
            [
                'category' => 'sales',
                'slug' => 'lead-management',
                'title' => 'إدارة وتوزيع العملاء المحتملين',
                'summary' => 'كل عميل يصله مسؤول المبيعات المناسب فوراً',
                'description' => 'استقبل العملاء من كل القنوات، ووزّعهم آلياً وإلكترونياً على الفريق بالتساوي، دون أن يضيع أي عميل أو يتأخر الرد عليه.',
                'points' => [
                    'توزيع آلي وعادل حسب الفرع أو الفريق',
                    'مسؤول وموعد متابعة لكل عميل',
                    'وسوم وملاحظات داخلية على ملف العميل',
                    'سجل كامل لكل تواصل مع العميل',
                ],
                'how_it_works' => [
                    ['title' => 'وصول رسالة', 'icon' => 'message-square'],
                    ['title' => 'إسناد آلي', 'icon' => 'user-check'],
                    ['title' => 'متابعة المندوب', 'icon' => 'check-circle'],
                ],
                'icon' => 'user-plus',
                'cta_label' => 'جرّبها مجاناً الآن',
                'cta_url' => '/register',
            ],
            [
                'category' => 'sales',
                'slug' => 'deals-pipeline',
                'title' => 'مسار الفرص والصفقات',
                'summary' => 'تابع كل صفقة من أول رسالة حتى الإغلاق',
                'description' => 'لوحة مرئية واضحة لمراحل البيع تمنحك رؤية فورية لقيمة كل صفقة ونسبة تقدمها وتوقيت إغلاقها المتوقع.',
                'points' => ['مراحل بيع مرنة وقابلة للتخصيص', 'تنبيهات الصفقات المتوقفة', 'ربط مباشر بمحادثات العميل', 'توقعات المبيعات الشهرية'],
                'how_it_works' => [
                    ['title' => 'إنشاء فرصة', 'icon' => 'plus-circle'],
                    ['title' => 'تحديث المرحلة', 'icon' => 'git-branch'],
                    ['title' => 'إغلاق الصفقة', 'icon' => 'trophy'],
                ],
                'icon' => 'kanban',
            ],
            [
                'category' => 'sales',
                'slug' => 'deal-rooms-esign',
                'title' => 'غرف الصفقات والتوقيع الإلكتروني',
                'summary' => 'مساحة واحدة تجمعك بالعميل حتى التوقيع',
                'description' => 'شارك عروض الأسعار، العقود، والملفات في غرفة رقمية خاصة بالعميل مع إمكانية الاعتماد والتوقيع الإلكتروني الفوري.',
                'points' => ['رابط خاص وآمن لكل صفقة', 'تتبع فتح العميل للعرض', 'توقيع إلكتروني معتمد', 'أرشفة تلقائية للعقود'],
                'how_it_works' => [
                    ['title' => 'تجهيز الغرفة', 'icon' => 'folder'],
                    ['title' => 'مراجعة العميل', 'icon' => 'eye'],
                    ['title' => 'توقيع فوري', 'icon' => 'pen-tool'],
                ],
                'icon' => 'file-signature',
            ],
            [
                'category' => 'sales',
                'slug' => 'commissions-incentives',
                'title' => 'العمولات والحوافز',
                'summary' => 'تحفيزات المبيعات بكل الشفافية والدقة',
                'description' => 'احتسب عمولات المندوبين ومديري الفرق تلقائياً بناءً على الصفقات المغلقة والهدف الشهري دون أي جداول يدوية.',
                'points' => ['قواعد عمولة مرنة حسب الهدف', 'احتساب لحظي فور إغلاق الصفقة', 'لوحة شفافية لكل مندوب', 'تقارير جاهزة للرواتب'],
                'how_it_works' => [
                    ['title' => 'إغلاق صفقة', 'icon' => 'check'],
                    ['title' => 'تطبيق النسبة', 'icon' => 'percent'],
                    ['title' => 'اعتماد العمولة', 'icon' => 'award'],
                ],
                'icon' => 'trophy',
            ],
            [
                'category' => 'sales',
                'slug' => 'b2b-lead-hunter',
                'title' => 'صائد الشركات B2B',
                'summary' => 'اكتشف شركات مستهدفة وأنشئ اتصالاتك',
                'description' => 'ابحث عن الشركات والأنشطة التجارية المستهدفة واستخرج بيانات التواصل لتغذية فريق المبيعات بفرص جديدة يومياً.',
                'points' => ['بحث حسب النشاط والمدينة', 'استخراج بيانات التواصل مباشرة', 'إضافة فورية إلى مسار المبيعات', 'تجنب تكرار العملاء المسجلين'],
                'how_it_works' => [
                    ['title' => 'تحديد القطاع', 'icon' => 'search'],
                    ['title' => 'استخراج النتائج', 'icon' => 'database'],
                    ['title' => 'بدء التواصل', 'icon' => 'send'],
                ],
                'icon' => 'crosshair',
            ],
            [
                'category' => 'communication',
                'slug' => 'unified-inbox',
                'title' => 'صندوق وارد موحد للواتساب والقنوات',
                'summary' => 'كل الرسائل والمحادثات في شاشة واحدة',
                'description' => 'اجمع رسائل الواتساب والقنوات الأخرى في صندوق وارد مشترك للفريق مع صلاحيات واضحة ومنع تداخل الردود.',
                'points' => ['تعدد الأرقام في شاشة واحدة', 'تحويل المحادثة بين المندوبين', 'ردود سريعة وقوالب جاهزة', 'إشعارات فورية للمحادثات الجديدة'],
                'how_it_works' => [
                    ['title' => 'وصول المحادثة', 'icon' => 'inbox'],
                    ['title' => 'توجيه تلقائي', 'icon' => 'route'],
                    ['title' => 'رد موثق', 'icon' => 'message-circle'],
                ],
                'icon' => 'inbox',
            ],
            [
                'category' => 'communication',
                'slug' => 'pbx-calls',
                'title' => 'ربط السنترال والمكالمات',
                'summary' => 'اتصل بسلاسة مع فتح ملف العميل',
                'description' => 'اربط نظام السنترال السحابي أو IP-PBX لإجراء المكالمات بنقرة واحدة وتسجيلها تلقائياً داخل سجل العميل.',
                'points' => ['اتصال بنقرة واحدة Click-to-Call', 'إظهار بطاقة العميل فور الرنين', 'تسجيل المكالمات وحفظها بالملف', 'تكامل Yeastar & SIP Trunks'],
                'how_it_works' => [
                    ['title' => 'رنين أو اتصال', 'icon' => 'phone'],
                    ['title' => 'ظهور الملف', 'icon' => 'user'],
                    ['title' => 'حفظ التسجيل', 'icon' => 'mic'],
                ],
                'icon' => 'phone-call',
            ],
            [
                'category' => 'communication',
                'slug' => 'appointments-bookings',
                'title' => 'المواعيد والحجوزات',
                'summary' => 'جدول مواعيدك والمقابلات بضغطة زر',
                'description' => 'نظّم مواعيد الاجتماعات والمعاينات وأرسل تذكيرات تلقائية للعملاء والمندوبين عبر الواتساب.',
                'points' => ['تقويم موحد للفريق', 'تذكيرات تلقائية قبل الموعد', 'ربط الموعد بملف الصفقة', 'تقليل نسبة عدم الحضور'],
                'how_it_works' => [
                    ['title' => 'حجز الموعد', 'icon' => 'calendar'],
                    ['title' => 'إرسال تذكير', 'icon' => 'bell'],
                    ['title' => 'إتمام اللقاء', 'icon' => 'check-square'],
                ],
                'icon' => 'calendar',
            ],
            [
                'category' => 'communication',
                'slug' => 'support-tickets',
                'title' => 'طلبات الدعم والتذاكر',
                'summary' => 'تحويل أي استفسار لمهمة متابعة',
                'description' => 'حوّل شكاوى وطلبات ما بعد البيع إلى تذاكر متابعة مرتبطة بسجل العميل مع تحديد الأولوية ووقت الحل.',
                'points' => ['إنشاء تذكرة من داخل المحادثة', 'تتبع حالة الحل والأولوية', 'إسناد لفريق الدعم المختص', 'قياس رضا العميل بعد الإغلاق'],
                'how_it_works' => [
                    ['title' => 'فتح التذكرة', 'icon' => 'ticket'],
                    ['title' => 'معالجة الطلب', 'icon' => 'wrench'],
                    ['title' => 'إغلاق وتقييم', 'icon' => 'star'],
                ],
                'icon' => 'ticket',
            ],
            [
                'category' => 'ai',
                'slug' => 'soli-ai-assistant',
                'title' => 'المساعد الذكي Soli AI',
                'summary' => 'رد ذكي يعمل على مدار الساعة',
                'description' => 'مساعد ذكي مدرب على بيانات شركتك يرد على العملاء بالعربية الفصحى والعامية ويؤهل الفرص قبل تحويلها للمندوب.',
                'points' => ['فهم العربية الفصحى والعامية', 'تأهيل العميل وتحديد احتياجه', 'تلخيص المحادثات الطويلة', 'تحويل سلس للمندوب عند الحاجة'],
                'how_it_works' => [
                    ['title' => 'سؤال العميل', 'icon' => 'message-circle'],
                    ['title' => 'تحليل Soli AI', 'icon' => 'cpu'],
                    ['title' => 'رد وتأهيل', 'icon' => 'sparkles'],
                ],
                'icon' => 'bot',
            ],
            [
                'category' => 'ai',
                'slug' => 'smart-interactive-proposals',
                'title' => 'العروض التفاعلية الذكية',
                'summary' => 'عروض تحسب تلقائياً في ثوانٍ',
                'description' => 'أنشئ عروض أسعار احترافية وتفاعلية بالذكاء الاصطناعي مع حساب الإجماليات والضرائب والخصومات تلقائياً.',
                'points' => ['قوالب عروض جاهزة واحترافية', 'حساب تلقائي للبنود والضرائب', 'مشاركة فورية عبر الواتساب', 'إشعار لحظي عند مشاهدة العرض'],
                'how_it_works' => [
                    ['title' => 'اختيار المنتجات', 'icon' => 'list'],
                    ['title' => 'توليد العرض', 'icon' => 'file-text'],
                    ['title' => 'إرسال وموافقة', 'icon' => 'send'],
                ],
                'icon' => 'file-text',
            ],
            [
                'category' => 'admin-security',
                'slug' => 'data-isolation-security',
                'title' => 'عزل وأمان البيانات',
                'summary' => 'معمارية Multi-Tenant مع عزل وتشفير',
                'description' => 'حماية كاملة لبيانات شركتك وعملائك عبر عزل تام لكل حساب، تشفير SSL، وصلاحيات دقيقة للمستخدمين.',
                'points' => ['عزل تام لكل شركة (Multi-Tenant)', 'تشفير 256-bit SSL للبيانات', 'صلاحيات مخصصة لكل دور وظيفي', 'سجل نشاط كامل للتدقيق'],
                'how_it_works' => [
                    ['title' => 'تشفير الاتصال', 'icon' => 'lock'],
                    ['title' => 'تطبيق الصلاحيات', 'icon' => 'shield'],
                    ['title' => 'تدقيق مستمر', 'icon' => 'activity'],
                ],
                'icon' => 'shield-check',
            ],
            [
                'category' => 'admin-security',
                'slug' => 'branches-realtime-reports',
                'title' => 'الفروع والتقارير اللحظية',
                'summary' => 'رؤية شاملة لأداء كل فرع وفريق',
                'description' => 'قارن أداء الفروع والمندوبين، معدلات التحويل، وسرعة الاستجابة من خلال لوحات تحكم وتقارير لحظية.',
                'points' => ['إدارة فروع متعددة من مكان واحد', 'تقارير سرعة الرد والإغلاق', 'مؤشرات أداء المندوبين', 'تصدير التقارير بضغطة زر'],
                'how_it_works' => [
                    ['title' => 'جمع البيانات', 'icon' => 'bar-chart-2'],
                    ['title' => 'مقارنة الفروع', 'icon' => 'building'],
                    ['title' => 'اتخاذ القرار', 'icon' => 'trending-up'],
                ],
                'icon' => 'bar-chart-3',
            ],
            [
                'category' => 'admin-security',
                'slug' => 'integrations-mobile-apps',
                'title' => 'التكاملات وتطبيقات الجوال',
                'summary' => 'بياناتك بين يديك وربط مع أنظمتك',
                'description' => 'اربط سيلزواي بمتجرك وأنظمتك عبر Webhooks وAPI، وتابع فريقك وعملاءك من أي مكان.',
                'points' => ['ربط مع المتاجر والنماذج الإعلانية', 'واجهة برمجية API وWebhooks', 'وصول سريع من الجوال', 'مزامنة لحظية للبيانات'],
                'how_it_works' => [
                    ['title' => 'ربط المصدر', 'icon' => 'plug'],
                    ['title' => 'مزامنة فورية', 'icon' => 'refresh-cw'],
                    ['title' => 'متابعة مستمرة', 'icon' => 'smartphone'],
                ],
                'icon' => 'plug',
            ],
        ];

        foreach ($features as $index => $item) {
            Feature::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'category_id' => $categoryIds[$item['category']],
                    'group' => 'home',
                    'title' => $item['title'],
                    'summary' => $item['summary'],
                    'badge' => null,
                    'description' => $item['description'],
                    'points' => $item['points'],
                    'how_it_works' => $item['how_it_works'],
                    'icon' => $item['icon'],
                    'cta_label' => $item['cta_label'] ?? null,
                    'cta_url' => $item['cta_url'] ?? null,
                    'sort_order' => $index + 1,
                    'status' => ContentStatus::Published,
                ],
            );
        }
    }

    private function seedPageFeatures(): void
    {
        $categories = [
            'whatsapp-unified-management' => ['name' => 'إدارة الواتساب الموحدة', 'sort_order' => 1],
            'sell-ai-assistant' => ['name' => 'المساعد الذكي (Sell AI)', 'sort_order' => 2],
            'cloud-ip-pbx' => ['name' => 'السنترال السحابي (IP-PBX)', 'sort_order' => 3],
            'sales-pipelines' => ['name' => 'خطوط أنابيب المبيعات', 'sort_order' => 4],
        ];

        $categoryIds = [];
        foreach ($categories as $slug => $data) {
            $category = FeatureCategory::updateOrCreate(
                ['slug' => $slug],
                ['name' => $data['name'], 'group' => 'features', 'sort_order' => $data['sort_order']],
            );
            $categoryIds[$slug] = $category->id;
        }

        $features = [
            [
                'category' => 'whatsapp-unified-management',
                'slug' => 'page-unified-sales-inbox',
                'title' => 'صندوق وارد موحد لفريق المبيعات',
                'badge' => 'بدون حد أقصى للأجهزة',
                'description' => 'استقبل جميع محادثات العملاء من أرقام واتساب متعددة في لوحة تحكم واحدة مع توزيع تلقائي ذكي على المندوبين.',
                'icon' => 'message-square',
            ],
            [
                'category' => 'sell-ai-assistant',
                'slug' => 'page-ai-sales-assistant',
                'title' => 'مساعد مبيعات آلي يعمل بالذكاء الاصطناعي',
                'badge' => 'دعم لغة عربية فصحى وعامية',
                'description' => 'AI ذكي يرد على استفسارات العملاء على مدار الساعة، يصنف نية الشراء، ويؤهل العملاء المحتملين قبل تحويلهم للبشر.',
                'icon' => 'cpu',
            ],
            [
                'category' => 'cloud-ip-pbx',
                'slug' => 'page-yeastar-pbx-integration',
                'title' => 'ربط كامل مع سنترال Yeastar P-Series',
                'badge' => 'تكامل مباشر مع IP-PBX',
                'description' => 'اتصال بنقرة واحدة (Click-to-Call)، تسجيل المكالمات تلقائياً، وظهور بطاقة العميل على الشاشة فور رنين الهاتف.',
                'icon' => 'phone',
            ],
            [
                'category' => 'sales-pipelines',
                'slug' => 'page-leads-pipeline',
                'title' => 'إدارة العملاء المحتملين (Leads Pipeline)',
                'badge' => 'تحديثات لحظية للبيانات',
                'description' => 'تابع رحلة كل عميل من أول رسالة وحتى إتمام التعاقد عبر مراحل مرئية وسهلة التخصيص.',
                'icon' => 'zap',
            ],
            [
                'category' => 'whatsapp-unified-management',
                'slug' => 'page-quick-replies-templates',
                'title' => 'الردود السريعة والقوالب المعتمدة',
                'badge' => 'سرعة في الرد بنسبة 300%',
                'description' => 'أرسل عروض الأسعار والكتالوجات بنقرة واحدة باستخدام قوالب واتساب الرسمية المعتمدة.',
                'icon' => 'check-circle',
            ],
            [
                'category' => 'sales-pipelines',
                'slug' => 'page-multi-tenant-security',
                'title' => 'أمان تام وعزل بيانات (Multi-Tenant)',
                'badge' => 'حماية مطابقة للمعايير العالمية',
                'description' => 'تشفير كامل للبيانات بقوة 256-bit SSL مع استضافة آمنة تضمن خصوصية شركتك وعملائك بالكامل.',
                'icon' => 'shield',
            ],
        ];

        foreach ($features as $index => $item) {
            Feature::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'category_id' => $categoryIds[$item['category']],
                    'group' => 'features',
                    'title' => $item['title'],
                    'summary' => null,
                    'badge' => $item['badge'],
                    'description' => $item['description'],
                    'points' => null,
                    'how_it_works' => null,
                    'icon' => $item['icon'],
                    'cta_label' => 'اكتشف المزيد',
                    'cta_url' => '/register',
                    'sort_order' => $index + 1,
                    'status' => ContentStatus::Published,
                ],
            );
        }
    }
}
