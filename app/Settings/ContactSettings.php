<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ContactSettings extends Settings
{
    /** International format, digits only (e.g. 201212020075). */
    public string $whatsapp_number;

    /** Supports {name}, {phone} and {message}. */
    public string $whatsapp_message_template;

    public static function group(): string
    {
        return 'contact';
    }
}
