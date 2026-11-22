<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

final class ComparisonBlock extends Block
{
    public static function fields(): array
    {
        return [
            ...static::heading(false),
            TextInput::make('feature_label')->label('عنوان عمود الميزة'),
            TextInput::make('us_label')->label('عنوان عمود سيلزواي'),
            TextInput::make('them_label')->label('عنوان عمود المنافسين'),
            Repeater::make('rows')
                ->label('الصفوف')
                ->schema([
                    TextInput::make('feature')->label('الميزة')->required(),
                    TextInput::make('us')->label('سيلزواي')->required(),
                    TextInput::make('them')->label('الأنظمة القديمة')->required(),
                ])
                ->columns(3)
                ->addActionLabel('إضافة صف')
                ->columnSpanFull(),
        ];
    }
}
