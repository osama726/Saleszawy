<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use Filament\Forms\Components\TextInput;

final class ContactBlock extends Block
{
    public static function fields(): array
    {
        return [
            ...static::heading(false),
            static::bullets('points', 'النقاط تحت العنوان'),
            TextInput::make('whatsapp_label')->label('نص زر الواتساب'),
            TextInput::make('whatsapp_number')->label('رقم الواتساب (للعرض)'),
            TextInput::make('whatsapp_url')->label('رابط الواتساب')->columnSpanFull(),
            TextInput::make('privacy_note')->label('ملاحظة الخصوصية')->columnSpanFull(),
            TextInput::make('form_badge')->label('شارة الفورم'),
            TextInput::make('form_title')->label('عنوان الفورم'),
            TextInput::make('name_label')->label('حقل الاسم: العنوان'),
            TextInput::make('name_placeholder')->label('حقل الاسم: النص الإرشادي'),
            TextInput::make('phone_label')->label('حقل الهاتف: العنوان'),
            TextInput::make('phone_placeholder')->label('حقل الهاتف: النص الإرشادي'),
            TextInput::make('message_label')->label('حقل الرسالة: العنوان'),
            TextInput::make('message_placeholder')->label('حقل الرسالة: النص الإرشادي'),
            TextInput::make('submit_label')->label('نص زر الإرسال')->columnSpanFull(),
        ];
    }
}
