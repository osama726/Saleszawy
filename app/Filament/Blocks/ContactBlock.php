<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

final class ContactBlock extends Block
{
    public static function fields(): array
    {
        return [
            ...static::heading(false),
            TextInput::make('whatsapp_label')->label('نص زر الواتساب'),
            TextInput::make('whatsapp_number')->label('رقم الواتساب (للعرض)'),
            TextInput::make('whatsapp_url')->label('رابط الواتساب')->columnSpanFull(),
            TextInput::make('privacy_note')->label('ملاحظة الخصوصية')->columnSpanFull(),
            TextInput::make('form_title')->label('عنوان الفورم')->columnSpanFull(),
            TextInput::make('submit_label')->label('نص زر الإرسال')->columnSpanFull(),
        ];
    }
}
