<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Models\Faq;
use Illuminate\Database\Seeder;

/**
 * Seeds the FAQ shown on the home page of the current site.
 * NOTE: the site shows the 2nd answer collapsed, so it is written from the site's own pricing text (TODO confirm).
 */
class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'كيف تضمنون سرية وعزل بيانات شركتي وعملائي؟',
                'answer' => 'تعمل منصة سيلزواي بمعمارية Multi-Tenant معزولة تماماً. بيانات كل شركة مشفرة ومحمية ولا يمكن لأي شركة ثانية الوصول إليها.',
            ],
            [
                'question' => 'هل يمكنني تجربة المنظومة مجاناً قبل الدفع؟',
                'answer' => 'نعم، تتيح سيلزواي تجربة مجانية لمدة 3 أيام بكامل الميزات، بدون الحاجة لبطاقة ائتمان، ويمكنك الترقية في أي وقت.', // TODO confirm
            ],
        ];

        foreach ($faqs as $index => $faq) {
            Faq::updateOrCreate(
                ['group' => 'home', 'question' => $faq['question']],
                $faq + [
                    'group' => 'home',
                    'sort_order' => $index + 1,
                    'status' => ContentStatus::Published,
                ],
            );
        }
    }
}
