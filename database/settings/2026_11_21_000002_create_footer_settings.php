<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('footer.description', 'نظام تشغيل المبيعات الأذكى للشركات لإدارة مسارات العملاء، أتمتة الواتساب، والتحليلات اللحظية.');
        $this->migrator->add('footer.copyright', '© {year} سيلزواي Saleszawy. جميع الحقوق محفوظة.');
        $this->migrator->add('footer.back_to_top_label', 'العودة للأعلى');
        $this->migrator->add('footer.floating_cta_enabled', true);
        $this->migrator->add('footer.floating_cta_label', 'تواصل مع مستشار المبيعات');
        $this->migrator->add('footer.floating_cta_url', 'https://wa.me/201212020075');
        $this->migrator->add('footer.socials', []);
    }
};
