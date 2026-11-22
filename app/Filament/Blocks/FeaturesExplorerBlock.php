<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

final class FeaturesExplorerBlock extends Block
{
    public static function fields(): array
    {
        return [
            ...static::heading(),
            static::cta('cta', 'زر الدعوة'),
        ];
    }
}
