<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Settings\ContactSettings;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageContactSettings extends SettingsPage
{
    protected static string $settings = ContactSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'واتساب فورم التواصل';

    protected static ?string $title = 'واتساب فورم التواصل';

    protected static ?int $navigationSort = 12;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('الرابط اللي بيتفتح بعد إرسال الفورم')
                ->schema([
                    TextInput::make('whatsapp_number')
                        ->label('رقم الواتساب')
                        ->helperText('بالصيغة الدولية وبدون + أو مسافات. مثال: 201212020075')
                        ->required()
                        ->regex('/^\+?[0-9\s]{8,20}$/')
                        ->maxLength(20),
                    Textarea::make('whatsapp_message_template')
                        ->label('نص الرسالة')
                        ->helperText('المتغيرات: {name} الاسم، {phone} الهاتف، {message} الرسالة (لو الزائر كتبها)')
                        ->required()
                        ->rows(5)
                        ->maxLength(1000),
                ]),
        ]);
    }
}
