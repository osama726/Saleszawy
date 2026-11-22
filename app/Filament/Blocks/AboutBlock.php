<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

final class AboutBlock extends Block
{
    public static function fields(): array
    {
        return [
            ...static::heading(false),
            Textarea::make('description')->label('الوصف')->rows(3)->columnSpanFull(),
            Repeater::make('highlights')
                ->label('النقاط البارزة')
                ->schema([
                    TextInput::make('title')->label('العنوان')->required(),
                    TextInput::make('text')->label('النص')->required(),
                ])
                ->columns(2)
                ->addActionLabel('إضافة نقطة')
                ->columnSpanFull(),
        ];
    }
}
