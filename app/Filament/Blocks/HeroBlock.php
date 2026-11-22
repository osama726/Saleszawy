<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

final class HeroBlock extends Block
{
    public static function fields(): array
    {
        return [
            TextInput::make('badge_label')->label('الشارة: العنوان'),
            TextInput::make('badge_text')->label('الشارة: النص'),
            TextInput::make('title')->label('العنوان الرئيسي')->required()->columnSpanFull(),
            Textarea::make('description')->label('الوصف')->rows(3)->columnSpanFull(),
            static::cta('primary_cta', 'الزر الأساسي'),
            static::cta('secondary_cta', 'الزر الثانوي'),
            TextInput::make('note')->label('ملاحظة تحت الأزرار')->columnSpanFull(),
            Repeater::make('stats')
                ->label('الأرقام')
                ->schema([
                    TextInput::make('value')->label('القيمة')->required(),
                    TextInput::make('label')->label('الوصف')->required(),
                ])
                ->columns(2)
                ->maxItems(4)
                ->addActionLabel('إضافة رقم')
                ->columnSpanFull(),
        ];
    }
}
