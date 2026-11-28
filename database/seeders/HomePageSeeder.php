<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BlockType;
use App\Enums\ContentStatus;
use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Seeds the home page with the content of the current site.
 * Safe to re-run: it rebuilds the blocks of the "home" page.
 *
 * NOTE: values marked (TODO) are guessed final values of the animated counters; confirm them.
 */
class HomePageSeeder extends Seeder
{
    public function run(): void
    {
        $page = Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'الرئيسية',
                'seo_title' => 'سيلزواي Saleszawy - نظام تشغيل المبيعات الأذكى للشركات',
                'seo_description' => 'سيلزواي: نظام تشغيل المبيعات المتكامل للشركات. إدارة العملاء المحتملين، أتمتة الواتساب، صائد الشركات B2B، مساعد Soli AI، غرف الصفقات الذكية، وحساب العمولات.',
                'status' => ContentStatus::Published,
                'published_at' => now(),
            ],
        );

        $page->blocks()->delete();

        foreach ($this->orderedBlocks() as $index => [$type, $data]) {
            $page->blocks()->create([
                'type' => $type,
                'data' => $data,
                'sort_order' => $index + 1,
                'is_visible' => true,
            ]);
        }
    }

    /**
     * Order of the sections on the live site.
     *
     * @var array<int, BlockType>
     */
    private const ORDER = [
        BlockType::Hero,
        BlockType::ProblemSolution,
        BlockType::Comparison,
        BlockType::Showcase,
        BlockType::FeaturesExplorer,
        BlockType::Pbx,
        BlockType::Integrations,
        BlockType::Steps,
        BlockType::Stats,
        BlockType::Partners,
        BlockType::Blog,
        BlockType::Faq,
        BlockType::About,
        BlockType::Pricing,
        BlockType::Contact,
        BlockType::Cta,
    ];

    /**
     * @return array<int, array{0: BlockType, 1: array<string, mixed>}>
     */
    private function orderedBlocks(): array
    {
        $position = [];
        foreach (self::ORDER as $index => $type) {
            $position[$type->value] = $index;
        }

        $blocks = $this->blocks();
        usort($blocks, static fn (array $a, array $b): int => $position[$a[0]->value] <=> $position[$b[0]->value]);

        return $blocks;
    }

    /**
     * @return array<int, array{0: BlockType, 1: array<string, mixed>}>
     */
    private function blocks(): array
    {
        return [
            [BlockType::Hero, [
                'badge_label' => 'الجيل الجديد',
                'badge_text' => 'منظومة مبيعات مدعومة بـ DeepSeek · Groq · OpenAI',
                'title' => 'نظام المبيعات الأذكى لشركتك.. من أول تواصل حتى إغلاق الصفقة',
                'description' => 'أدر مبيعاتك، فروعك، وعملاءك في منصة واحدة فائقة السرعة. ادمج محادثات الواتساب السحابية، وفعّل المساعد الذكي Soli AI لتوليد العروض التفاعلية وإغلاق الصفقات بضغطة زر.',
                'primary_cta' => ['label' => 'ابدأ شركتك مجاناً الآن', 'url' => '/register'],
                'secondary_cta' => ['label' => 'معاينة حية للنظام', 'url' => '#preview'],
                'note' => 'تجربة مجانية 3 أيام · بدون بطاقة ائتمان · ترقية في أي وقت',
                'stats' => [
                    ['value' => '+40%', 'label' => 'سرعة إغلاق الصفقات'], // TODO confirm
                    ['value' => '24/7', 'label' => 'أتمتة ومساعد Soli AI'],
                    ['value' => '100%', 'label' => 'عزل سحابي Multi-Tenant'], // TODO confirm
                    ['value' => '0 دقيقة', 'label' => 'تأخير في توزيع العملاء'],
                ],
            ]],

            [BlockType::Partners, [
                'eyebrow' => 'ثقة وشركاء النجاح',
                'title' => 'شركات ومؤسسات تعتمد على سيلزواي يومياً',
                'subtitle' => 'متاجر، شركات عقارية، شحن، وتطوير من مختلف الدول والمناطق تدار مبيعاتها عبر المنظومة.',
            ]],

            [BlockType::ProblemSolution, [
                'eyebrow' => 'لماذا سيلزواي؟',
                'title' => 'حين تتوزع المحادثات.. تضيع الفرص البيعية',
                'subtitle' => 'رسائل على هواتف متفرقة، تعليقات بلا رد، وعميل يعيد شرح طلبه لكل موظف. سيلزواي يعيد ترتيب ذلك بالكامل.',
                'before_label' => 'قبل المنظومة',
                'after_label' => 'مع سيلزواي',
                'after_title' => 'نظام موحد وأرباح مضاعفة',
                'points' => [
                    'صندوق وارد واحد موحد يجمع كافة أرقام الواتساب والقنوات.',
                    'سجل العميل كاملاً واقتراحات Soli AI أمام الموظف أثناء الرد.',
                    'توزيع آلي للعملاء وتعيين مسؤول وموعد متابعة محدد لكل فرصة.',
                    'تقارير دقيقة لحظية واحتساب آلي للعمولات والحوافز.',
                ],
            ]],

            [BlockType::Showcase, [
                'eyebrow' => 'إدارة علاقات العملاء عبر قنواتك',
                'title' => 'عملاء ومبيعات وخدمة، في مكان واحد.',
                'subtitle' => 'اربط المحادثة بما يليها من متابعة أو حجز أو طلب دعم، مع أدوات تتكامل حول ملف العميل.',
                'items' => [
                    [
                        'title' => 'محادثة واحدة لكل عميل',
                        'description' => 'اجمع رسائل الحسابات المرتبطة بالعميل عبر القنوات المدعومة في محادثة واحدة مرتبة زمنيًا، ليواصل فريقك من حيث توقف.',
                        'points' => ['تعيين للمستخدمين والأقسام، يدويًا أو تلقائيًا', 'ملاحظات داخلية ووسوم وردود سريعة'],
                    ],
                    [
                        'title' => 'ذكاء اصطناعي يستند إلى معرفتك',
                        'description' => 'استخدم قاعدة معرفتك ومصادرك المتصلة لدعم الردود وتلخيص المحادثات، وفق إعداد مؤسستك.',
                        'points' => ['OpenAI و Google Gemini و Moonshot Kimi بحسب الإعداد', 'رصيد مُدار أو مفتاحك الخاص لدى المزوّد المدعوم'],
                    ],
                    [
                        'title' => 'مواعيد مرتبطة بالمحادثة',
                        'description' => 'نظّم الخدمات والموارد وأوقات العمل، وساعد العميل على حجز الموعد المناسب.',
                        'points' => ['التحقق من الأوقات المتاحة قبل الحجز', 'تعديل الموعد أو إلغاؤه وفق الصلاحيات والسياسات'],
                    ],
                    [
                        'title' => 'متابعة واضحة لكل فرصة',
                        'description' => 'تابع العملاء المحتملين عبر مراحل البيع بدل أن تبقى الفرصة داخل الرسائل.',
                        'points' => ['مسؤول وقيمة وموعد متابعة لكل فرصة', 'عرض المراحل وسجل الانتقالات'],
                    ],
                    [
                        'title' => 'طلبات دعم لا تضيع',
                        'description' => 'حوّل احتياج العميل إلى تذكرة أو مهمة يمكن لفريقك متابعة حالتها.',
                        'points' => ['حالات وأولويات وتعيين ومواعيد خدمة', 'تعليقات ومرفقات ومهام مرتبطة أو مستقلة'],
                    ],
                    [
                        'title' => 'بيانات تجارتك بجوار المحادثة',
                        'description' => 'اربط تاجر لينك والأنظمة المحاسبية المدعومة لإتاحة بيانات المنتجات والطلبات المصرح بها.',
                        'points' => ['طلبات وفواتير ومخزون بحسب التكامل المتصل', 'Webhooks وواجهات API وفق الصلاحيات'],
                    ],
                    [
                        'title' => 'قياس رضا وخدمة العملاء',
                        'description' => 'ارفع جودة الخدمة واستطلع آراء العملاء تلقائياً بعد إغلاق المحادثات أو إتمام الطلبات.',
                        'points' => ['استطلاعات رأي فورية عبر القنوات', 'تقارير أداء وتقييم أداء الموظفين'],
                    ],
                ],
                'footer_text' => 'كل ذلك.. وأكثر في اشتراك واحد. ابدأ تجربتك المجانية لمدة 3 أيام بكامل الميزات.',
                'footer_cta' => ['label' => 'شاهد الباقات', 'url' => '#pricing'],
            ]],

            [BlockType::FeaturesExplorer, [
                'eyebrow' => 'مستكشف القدرات',
                'title' => 'استكشف كل ما تستطيع سيلزواي فعله لفريقك',
                'subtitle' => '14 قدرة متكاملة في منصة واحدة. اختر المجال، وافتح أي قدرة لترى تفاصيلها وكيف تعمل.',
                'cta' => ['label' => 'جرّبها مجاناً الآن', 'url' => '/register'],
            ]],

            [BlockType::Pbx, [
                'eyebrow' => 'تكامل الاتصالات والسنترال (IP-PBX)',
                'title' => 'اربط سنترالك وفريق الكول سنتر بمنظومة المبيعات',
                'description' => 'تكامل اختياري وسلس مع أنظمة السنترال مثل Yeastar P-Series وغيرها. يتيح للفريق إجراء المكالمات وعرض هوية المتصل وسجل المحادثة السابقة فور استقبال اتصال العميل.',
                'points' => [
                    'ربط امتدادات الموظفين تلقائياً',
                    'الاتصال بنقرة واحدة Click-to-Call',
                    'تكامل Yeastar & SIP Trunks',
                    'سياق العميل كاملاً فور الرنين',
                ],
            ]],

            [BlockType::Steps, [
                'eyebrow' => 'رحلة النجاح مع سيلزواي',
                'title' => 'كيف تبدأ وتضاعف مبيعاتك في 4 خطوات؟',
                'steps' => [
                    ['title' => 'أنشئ حساب شركتك', 'description' => 'اختر اسم النطاق الفرعي لشركتك وفعّل حسابك في أقل من 60 ثانية بكود التحقق عبر الواتساب.'],
                    ['title' => 'استورد أو اصطد عملاءك', 'description' => 'استورد ملفات Excel/CSV أو استخدم محرك صيد الشركات B2B لجلب عملاء مستهدفين فوراً.'],
                    ['title' => 'فعّل Soli AI والواتساب', 'description' => 'اربط رقم شركتك، ودع المساعد الذكي يوزع العملاء ويرد على الاستفسارات ويرسل العروض الرقمية.'],
                    ['title' => 'أغلق الصفقات وتابع الأرباح', 'description' => 'راقب أداء الموظفين، تفاعل العملاء في غرف الصفقات، واحسب العمولات والنمو المالي لحظياً.'],
                ],
            ]],

            [BlockType::Stats, [
                'items' => [
                    ['value' => '+10', 'label' => 'قنوات تواصل في صندوق موحد'], // TODO confirm
                    ['value' => '99.9%', 'label' => 'استقرار الخدمة والبنية السحابية'], // TODO confirm
                    ['value' => '3', 'label' => 'محركات ذكاء اصطناعي مدمجة'], // TODO confirm
                    ['value' => '3 أيام', 'label' => 'تجربة مجانية بكامل الميزات'],
                ],
            ]],

            [BlockType::Comparison, [
                'eyebrow' => 'المقارنة الشاملة',
                'title' => 'لماذا تختار سيلزواي على البرامج التقليدية؟',
                'feature_label' => 'الميزة والقدرة التنافسية',
                'us_label' => 'سيلزواي',
                'them_label' => 'الأنظمة القديمة / Excel',
                'rows' => [
                    ['feature' => 'الذكاء الاصطناعي والمساعد Soli AI', 'us' => 'مدمج بالكامل', 'them' => 'غير متاح'],
                    ['feature' => 'صندوق وارد موحد للواتساب والقنوات', 'us' => 'كل الأرقام والقنوات', 'them' => 'هواتف متفرقة'],
                    ['feature' => 'توزيع العملاء وتعيين المسؤول', 'us' => 'آلي ولحظي', 'them' => 'يدوي'],
                    ['feature' => 'غرف الصفقات والتوقيع الإلكتروني', 'us' => 'مدمجة', 'them' => 'غير متاح'],
                    ['feature' => 'احتساب العمولات والحوافز', 'us' => 'تلقائي', 'them' => 'جداول يدوية'],
                    ['feature' => 'ربط السنترال والمكالمات', 'us' => 'Yeastar & SIP', 'them' => 'غير متاح'],
                    ['feature' => 'عزل وأمان البيانات', 'us' => 'Multi-Tenant مشفر', 'them' => 'ملفات مشتركة'],
                ],
            ]],

            [BlockType::Pricing, [
                'eyebrow' => 'خطط واضحة وعادلة',
                'title' => 'اختر الباقة المناسبة لحجم أعمالك',
                'subtitle' => 'ابدأ بالتجربة المجانية لمدة 3 أيام، بدون الحاجة لبطاقة ائتمان، وقم بالترقية في أي وقت.',
                'monthly_label' => 'اشتراك شهري',
                'yearly_label' => 'اشتراك سنوي',
                'yearly_badge' => 'وفر 20%',
            ]],

            [BlockType::Integrations, [
                'eyebrow' => 'منظومة الحلول المترابطة',
                'title' => 'تكاملات وحلول متكاملة لاحتياجات أعمالك',
            ]],

            [BlockType::Faq, [
                'eyebrow' => 'الأسئلة الشائعة',
                'title' => 'كل ما تود معرفته عن منصة سيلزواي',
                'group' => 'home',
            ]],

            [BlockType::Blog, [
                'eyebrow' => 'مدونة سيلزواي وأسرار المبيعات',
                'title' => 'استراتيجيات حديثة لمضاعفة إيرادات شركتك',
                'limit' => 3,
            ]],

            [BlockType::About, [
                'eyebrow' => 'عن سيلزواي وخبرة الأنظمة',
                'title' => 'خبرة متراكمة في تطوير الاتصالات وبرمجيات الأعمال',
                'description' => 'تُطوّر منظومة سيلزواي وفق أعلى المعايير الهندسية لتوفير نظام CRM متكامل يربط المبيعات بالمحادثات اليومية. يتيح النظام بيئة عمل آمنة، أدواراً وصلاحيات دقيقة لكل موظف، وتحليلات مالية لحظية.',
                'highlights' => [
                    ['title' => 'حماية وتشفير تام', 'text' => '256-bit SSL'],
                    ['title' => 'خوادم سحابية عالية الاعتمادية', 'text' => 'بنية معزولة Multi-Tenant'],
                    ['title' => 'دعم فني ومستشارون متفرغون', 'text' => 'معك في كل خطوة'],
                ],
            ]],

            [BlockType::Contact, [
                'eyebrow' => 'فريق الدعم في خدمتك',
                'title' => 'هل لديك أي استفسار أو ترغب في عرض توضيحي مخصص؟',
                'whatsapp_label' => 'محادثة واتساب فورية',
                'whatsapp_number' => '+20 121 202 0075',
                'whatsapp_url' => 'https://wa.me/201212020075',
                'privacy_note' => 'بياناتك آمنة ولن تُشارك مع أي طرف.',
                'form_title' => 'أرسل استفسارك وسنعاود الاتصال بك فوراً',
                'submit_label' => 'إرسال الاستفسار والتواصل عبر الواتساب',
                'points' => [
                    'عرض توضيحي مخصص لطبيعة نشاطك وفروعك',
                    'ترشيح الباقة الأنسب لحجم فريق المبيعات',
                    'إجابة فورية حول الربط والعمولات والذكاء الاصطناعي',
                ],
                'form_badge' => 'نموذج استشارة سريع',
                'name_label' => 'الاسم الكريم',
                'name_placeholder' => 'مثال: أحمد محمد',
                'phone_label' => 'رقم الهاتف / الواتساب',
                'phone_placeholder' => '+20 1xx xxx xxxx',
                'message_label' => 'رسالتك (اختياري)',
                'message_placeholder' => 'اكتب رسالتك أو تفاصيل استفسارك...',
            ]],

            [BlockType::Cta, [
                'title' => 'جاهز تنقل مبيعاتك لمستوى آخر؟',
                'subtitle' => 'أنشئ حساب شركتك في أقل من 60 ثانية، وجرّب المنظومة كاملة مجاناً لمدة 3 أيام.',
                'primary_cta' => ['label' => 'ابدأ شركتك مجاناً الآن', 'url' => '/register'],
                'secondary_cta' => ['label' => 'تحدث مع مستشار المبيعات', 'url' => 'https://wa.me/201212020075'],
            ]],
        ];
    }
}
