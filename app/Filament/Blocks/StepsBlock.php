<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

final class StepsBlock extends Block
{
    public static function fields(): array
    {
        return [
            ...static::heading(false),
            Repeater::make('steps')
                ->label('الخطوات')
                ->schema([
                    TextInput::make('title')->label('العنوان')->required(),
                    Textarea::make('description')->label('الوصف')->rows(2),
                ])
                ->collapsible()
                ->addActionLabel('إضافة خطوة')
                ->columnSpanFull(),
        ];
    }
}
