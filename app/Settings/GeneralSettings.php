<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;

    public string $logo_text;

    public ?string $logo_badge;

    /** Path on the public disk (optional image logo). */
    public ?string $logo_path;

    public static function group(): string
    {
        return 'general';
    }
}
