<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

final class ProblemSolutionBlock extends Block
{
    public static function fields(): array
    {
        return [
            ...static::heading(),
            TextInput::make('before_label')->label('عنوان قبل المنظومة'),
            TextInput::make('after_label')->label('عنوان مع المنظومة'),
            TextInput::make('after_title')->label('عنوان الحل')->columnSpanFull(),
            static::bullets('points', 'نقاط الحل'),
        ];
    }
}
