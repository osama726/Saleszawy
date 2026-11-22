<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

final class BlogBlock extends Block
{
    public static function fields(): array
    {
        return [
            ...static::heading(false),
            TextInput::make('limit')->label('عدد المقالات')->numeric()->minValue(1)->maxValue(12)->default(3),
        ];
    }
}
