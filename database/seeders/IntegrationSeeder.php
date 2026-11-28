<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Models\Integration;
use Illuminate\Database\Seeder;

/**
 * Seeds the 3 integration cards of the current site.
 * Icon keys are suggestions for the frontend (the site does not expose its icons): edit them from the admin.
 */
class IntegrationSeeder extends Seeder
{
    public function run(): void
    {
        $integrations = [
            [
                'slug' => 'erp-accounting',
                'title' => 'تكامل أنظمة ERP والمحاسبة',
                'description' => 'ربط المبيعات والمخزون والفواتير مع الأنظمة المحاسبية وسراج وكريستال.',
                'tags' => ['سراج', 'كريستال', 'ERP'],
                'icon' => 'calculator',
            ],
            [
                'slug' => 'tagerlink-ecommerce',
                'title' => 'منصة تاجر Link للتجارة الإلكترونية',
                'description' => 'مزامنة الطلبات والمخزون التلقائية بجوار محادثة العميل على الواتساب.',
                'tags' => ['طلبات', 'مخزون', 'واتساب'],
                'icon' => 'shopping-cart',
            ],
            [
                'slug' => 'payment-gateways',
                'title' => 'بوابات الدفع الإلكتروني: يسر باي و Stripe',
                'description' => 'إنشاء روابط دفع فورية وإرسالها للعميل داخل غرفة الصفقات المباشرة.',
                'tags' => ['يسر باي', 'Stripe', 'روابط دفع'],
                'icon' => 'credit-card',
            ],
        ];

        foreach ($integrations as $index => $integration) {
            Integration::updateOrCreate(
                ['slug' => $integration['slug']],
                $integration + ['sort_order' => $index + 1, 'status' => ContentStatus::Published],
            );
        }
    }
}
