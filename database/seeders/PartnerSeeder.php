<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Models\Partner;
use Illuminate\Database\Seeder;

/**
 * Seeds the partner names shown on the current site (no logos: upload them from the admin).
 */
class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'مجموعة الأفق للتقنية',
            'متجر الريان العالمي',
            'وسام الشحن والخدمات',
            'عقارات المسار الفاخرة',
            'أكاديمية التعلم التفاعلي',
        ];

        foreach ($names as $index => $name) {
            Partner::updateOrCreate(
                ['name' => $name],
                ['sort_order' => $index + 1, 'status' => ContentStatus::Published],
            );
        }
    }
}
