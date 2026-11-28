<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('contact.whatsapp_number', '201212020075');
        $this->migrator->add(
            'contact.whatsapp_message_template',
            "مرحباً سيلزواي 👋\nأنا {name}\nرقمي: {phone}\n{message}",
        );
    }
};
