<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

final class PricingBlock extends Block
{
    public static function fields(): array
    {
        return [
            ...static::heading(),
            TextInput::make('monthly_label')->label('نص الاشتراك الشهري'),
            TextInput::make('yearly_label')->label('نص الاشتراك السنوي'),
            TextInput::make('yearly_badge')->label('شارة الخصم السنوي'),
        ];
    }
}
