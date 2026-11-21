<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ContactStatus: string implements HasColor, HasLabel
{
    case New = 'new';
    case InProgress = 'in_progress';
    case Done = 'done';
    case Spam = 'spam';

    public function getLabel(): string
    {
        return match ($this) {
            self::New => 'جديد',
            self::InProgress => 'قيد المتابعة',
            self::Done => 'تم',
            self::Spam => 'سبام',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::New => 'info',
            self::InProgress => 'warning',
            self::Done => 'success',
            self::Spam => 'danger',
        };
    }
}
