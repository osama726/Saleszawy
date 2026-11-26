<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BlockType;
use App\Enums\ContentStatus;
use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Seeds the "about" (من نحن) page with About (including Mission & Vision) and Contact blocks.
 * Safe to re-run: it rebuilds the blocks of the "about" page.
 */
class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        $page = Page::updateOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'من نحن',
                'seo_title' => 'من نحن | سيلزواي Saleszawy',
                'seo_description' => 'تعرّف على سيلزواي، خبرتنا في تطوير الاتصالات وبرمجيات الأعمال، رسالتنا، ورؤيتنا في تمكين فرق المبيعات.',
                'status' => ContentStatus::Published,
                'published_at' => now(),
            ],
        );

        $page->blocks()->delete();

        $blocks = [
            [
                'type' => BlockType::About,
                'data' => [
                    'eyebrow' => 'عن سيلزواي وخبرة الأنظمة',
                    'title' => 'خبرة متراكمة في تطوير الاتصالات وبرمجيات الأعمال',
                    'description' => 'تطوّر منظومة سيلزواي وفق أعلى المعايير الهندسية لتوفير نظام CRM متكامل يربط المبيعات بالمحادثات اليومية، يتيح النظام بيئة عمل آمنة، أدواراً وصلاحيات دقيقة لكل موظف، وتحليلات مالية لحظية تضمن نمو شركتك.',
                    'highlights' => [
                        ['title' => 'حماية وتشفير تام', 'text' => '256-bit SSL', 'icon' => 'lock'],
                        ['title' => 'خوادم سحابية عالية الاعتمادية', 'text' => 'بنية معزولة Multi-Tenant', 'icon' => 'server'],
                        ['title' => 'دعم فني ومستشارون متفرغون', 'text' => 'معك في كل خطوة', 'icon' => 'headphones'],
                    ],
                    'mission' => [
                        'title' => 'رسالتنا (Mission)',
                        'description' => 'تمكين فرق المبيعات والشركات الناشئة والمتوسطة في المنطقة العربية من أتمتة عمليات التواصل، القضاء على تشتت العمل بين التطبيقات، وتحويل كل محادثة واتساب إلى صفقة ناجحة بكل سهولة واحترافية.',
                    ],
                    'vision' => [
                        'title' => 'رؤيتنا (Vision)',
                        'description' => 'أن نكون المنظومة البرمجية الأولى والرائدة في دمج قنوات الاتصال السحابية والذكاء الاصطناعي مع أنظمة الـ CRM، لنكون الشريك التقني الموثوق لكل شركة تبحث عن التوسع والموثوقية.',
                    ],
                ],
            ],
            [
                'type' => BlockType::Contact,
                'data' => [
                    'eyebrow' => 'فريق الدعم في خدمتك',
                    'title' => 'هل لديك أي استفسار أو ترغب في عرض توضيحي مخصص؟',
                    'whatsapp_label' => 'محادثة واتساب فورية',
                    'whatsapp_number' => '+20 121 202 0075',
                    'whatsapp_url' => 'https://wa.me/201212020075',
                    'privacy_note' => 'بياناتك آمنة ولن تشارك مع أي طرف.',
                    'form_title' => 'أرسل استفسارك وسنعاود الاتصال بك فوراً',
                    'submit_label' => 'إرسال الاستفسار والتواصل عبر الواتساب',
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
}
