<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ButtonVariant: string implements HasLabel
{
    case Primary = 'primary';
    case Secondary = 'secondary';
    case Ghost = 'ghost';

    public function getLabel(): string
    {
        return match ($this) {
            self::Primary => 'أساسي',
            self::Secondary => 'ثانوي',
            self::Ghost => 'شفاف',
        };
    }
}
