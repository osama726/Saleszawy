<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageGeneralSettings extends SettingsPage
{
    protected static string $settings = GeneralSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'هوية الموقع';

    protected static ?string $title = 'هوية الموقع (الشعار والاسم)';

    protected static ?int $navigationSort = 10;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('الهوية')
                ->columns(2)
                ->schema([
                    TextInput::make('site_name')->label('اسم الموقع')->required()->maxLength(120),
                    TextInput::make('logo_text')->label('نص الشعار')->required()->maxLength(60),
                    TextInput::make('logo_badge')->label('شارة بجوار الشعار')->helperText('مثال: v2.0 AI')->maxLength(30),
                    FileUpload::make('logo_path')
                        ->label('صورة الشعار (اختياري)')
                        ->image()
                        ->disk('public')
                        ->directory('branding')
                        ->visibility('public'),
                ]),
        ]);
    }
}
