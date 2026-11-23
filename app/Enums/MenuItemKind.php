<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MenuItemKind: string implements HasLabel
{
    case Link = 'link';
    case Button = 'button';
    case Text = 'text';

    public function getLabel(): string
    {
        return match ($this) {
            self::Link => 'رابط',
            self::Button => 'زر',
            self::Text => 'نص بدون رابط',
        };
    }
}
