<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class FooterSettings extends Settings
{
    public string $description;

    /** Supports the {year} placeholder. */
    public string $copyright;

    public string $back_to_top_label;

    public bool $floating_cta_enabled;

    public string $floating_cta_label;

    public string $floating_cta_url;

    public array $socials;

    public static function group(): string
    {
        return 'footer';
    }
}
