<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

final class FaqBlock extends Block
{
    public static function fields(): array
    {
        return [
            ...static::heading(false),
            TextInput::make('group')->label('مجموعة الأسئلة (group)')->helperText('الأسئلة اللي هتتعرض من الـ API حسب المجموعة دي'),
        ];
    }
}
