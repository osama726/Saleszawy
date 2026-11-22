<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

final class StatsBlock extends Block
{
    public static function fields(): array
    {
        return [
            Repeater::make('items')
                ->label('الأرقام')
                ->schema([
                    TextInput::make('value')->label('القيمة')->required(),
                    TextInput::make('label')->label('الوصف')->required(),
                ])
                ->columns(2)
                ->addActionLabel('إضافة رقم')
                ->columnSpanFull(),
        ];
    }
}
