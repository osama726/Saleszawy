<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

final class ShowcaseBlock extends Block
{
    public static function fields(): array
    {
        return [
            ...static::heading(),
            Repeater::make('items')
                ->label('القدرات')
                ->schema([
                    TextInput::make('title')->label('العنوان')->required(),
                    Textarea::make('description')->label('الوصف')->rows(2),
                    static::bullets('points', 'النقاط'),
                ])
                ->collapsible()
                ->addActionLabel('إضافة قدرة')
                ->columnSpanFull(),
            TextInput::make('footer_text')->label('نص الشريط السفلي')->columnSpanFull(),
            static::cta('footer_cta', 'زر الشريط السفلي'),
        ];
    }
}
