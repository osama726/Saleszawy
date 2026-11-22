<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;

/**
 * One class per page-block type. Each class only describes its form fields;
 * adding a new block type never touches existing ones (see BlockRegistry).
 */
abstract class Block
{
    /**
     * @return array<int, mixed>
     */
    abstract public static function fields(): array;

    /**
     * @return array<int, mixed>
     */
    protected static function heading(bool $withSubtitle = true): array
    {
        $fields = [
            TextInput::make('eyebrow')->label('العنوان الصغير'),
            TextInput::make('title')->label('العنوان')->required(),
        ];

        if ($withSubtitle) {
            $fields[] = Textarea::make('subtitle')->label('الوصف')->rows(2)->columnSpanFull();
        }

        return $fields;
    }

    protected static function cta(string $name, string $label): Fieldset
    {
        return Fieldset::make($label)
            ->statePath($name)
            ->columns(2)
            ->schema([
                TextInput::make('label')->label('النص')->required(),
                TextInput::make('url')->label('الرابط')->required(),
            ]);
    }

    protected static function bullets(string $name, string $label): Repeater
    {
        return Repeater::make($name)
            ->label($label)
            ->simple(TextInput::make('text')->required())
            ->addActionLabel('إضافة نقطة')
            ->columnSpanFull();
    }
}
