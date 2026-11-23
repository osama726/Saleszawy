<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'سيلزواي Saleszawy');
        $this->migrator->add('general.logo_text', 'سيلزواي');
        $this->migrator->add('general.logo_badge', 'v2.0 AI');
        $this->migrator->add('general.logo_path', null);
    }
};
