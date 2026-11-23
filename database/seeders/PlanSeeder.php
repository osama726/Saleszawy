<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Models\Plan;
use Illuminate\Database\Seeder;

/**
 * Seeds the 3 plans shown on the current site.
 * NOTE: yearly prices are monthly x 12 with the 20% discount shown on the site (TODO confirm).
 */
class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $shared = [
            'مساعد Soli AI مدمج',
            'صندوق وارد موحد للواتساب',
            'غرف الصفقات الذكية',
            'تقارير وعمولات لحظية',
        ];

        $plans = [
            [
                'slug' => 'free-trial',
                'name' => 'تجربة مجانية (3 أيام) 🔥',
                'description' => 'باقة متكاملة لتشغيل وأتمتة المبيعات وتوسيع العمليات وزيادة التحويلات.',
                'price_monthly' => 0,
                'price_yearly' => 0,
                'trial_days' => 3,
                'max_sales_users' => 1,
                'max_leads' => 1000,
                'is_featured' => false,
                'badge' => null,
                'cta_label' => '🚀 ابدأ التجربة المجانية',
                'cta_url' => '/register?plan=1',
            ],
            [
                'slug' => 'diamond',
                'name' => 'الماسية',
                'description' => 'باقة متكاملة لتشغيل وأتمتة المبيعات وتوسيع العمليات وزيادة التحويلات.',
                'price_monthly' => 6500,
                'price_yearly' => 62400, // TODO confirm
                'trial_days' => 0,
                'max_sales_users' => 5,
                'max_leads' => 40000,
                'is_featured' => true,
                'badge' => 'الخيار الأكثر طلباً لفرق المبيعات',
                'cta_label' => 'اشترك الآن في الباقة',
                'cta_url' => '/register?plan=2',
            ],
            [
                'slug' => 'full-option',
                'name' => 'فول اوبشن',
                'description' => 'باقة لا محدودة لتشغيل المؤسسات والشركات الكبرى.',
                'price_monthly' => 8000,
                'price_yearly' => 76800, // TODO confirm
                'trial_days' => 0,
                'max_sales_users' => 50,
                'max_leads' => 999999,
                'is_featured' => false,
                'badge' => null,
                'cta_label' => 'اشترك الآن في الباقة',
                'cta_url' => '/register?plan=3',
            ],
        ];

        foreach ($plans as $index => $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan + [
                    'currency' => 'EGP',
                    'features' => $shared,
                    'status' => ContentStatus::Published,
                    'sort_order' => $index + 1,
                ],
            );
        }
    }
}
