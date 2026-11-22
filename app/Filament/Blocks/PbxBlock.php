<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

final class PbxBlock extends Block
{
    public static function fields(): array
    {
        return [
            ...static::heading(false),
            Textarea::make('description')->label('الوصف')->rows(3)->columnSpanFull(),
            static::bullets('points', 'النقاط'),
        ];
    }
}
