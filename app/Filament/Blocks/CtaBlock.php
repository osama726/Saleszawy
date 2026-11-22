<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

final class CtaBlock extends Block
{
    public static function fields(): array
    {
        return [
            TextInput::make('title')->label('العنوان')->required()->columnSpanFull(),
            Textarea::make('subtitle')->label('الوصف')->rows(2)->columnSpanFull(),
            static::cta('primary_cta', 'الزر الأساسي'),
            static::cta('secondary_cta', 'الزر الثانوي'),
        ];
    }
}
