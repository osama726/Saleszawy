<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MenuLocation: string implements HasLabel
{
    case Header = 'header';
    case Footer = 'footer';

    public function getLabel(): string
    {
        return match ($this) {
            self::Header => 'الهيدر (Navbar)',
            self::Footer => 'الفوتر',
        };
    }
}
