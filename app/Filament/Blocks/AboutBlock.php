<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;

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
                    TextInput::make('icon')->label('الأيقونة')->maxLength(100),
                ])
                ->columns(3)
                ->addActionLabel('إضافة نقطة')
                ->columnSpanFull(),
            Fieldset::make('رسالتنا (Mission)')
                ->statePath('mission')
                ->columns(1)
                ->schema([
                    TextInput::make('title')->label('العنوان')->maxLength(255),
                    Textarea::make('description')->label('النص')->rows(3),
                ]),
            Fieldset::make('رؤيتنا (Vision)')
                ->statePath('vision')
                ->columns(1)
                ->schema([
                    TextInput::make('title')->label('العنوان')->maxLength(255),
                    Textarea::make('description')->label('النص')->rows(3),
                ]),
        ];
    }
}
