<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Settings\FooterSettings;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageFooterSettings extends SettingsPage
{
    protected static string $settings = FooterSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static ?string $navigationLabel = 'إعدادات الفوتر';

    protected static ?string $title = 'إعدادات الفوتر';

    protected static ?int $navigationSort = 11;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('النصوص')
                ->schema([
                    Textarea::make('description')->label('وصف الفوتر')->rows(3)->required(),
                    TextInput::make('copyright')
                        ->label('نص الحقوق')
                        ->helperText('استخدم {year} لعرض السنة الحالية تلقائياً')
                        ->required(),
                    TextInput::make('back_to_top_label')->label('نص زر العودة للأعلى')->required(),
                ]),

            Section::make('زر التواصل العائم')
                ->columns(2)
                ->schema([
                    Toggle::make('floating_cta_enabled')->label('إظهار الزر')->inline(false)->columnSpanFull(),
                    TextInput::make('floating_cta_label')->label('النص')->required(),
                    TextInput::make('floating_cta_url')->label('الرابط')->required(),
                ]),

            Section::make('السوشيال ميديا')
                ->schema([
                    Repeater::make('socials')
                        ->label('الحسابات')
                        ->schema([
                            Select::make('platform')
                                ->label('المنصة')
                                ->options([
                                    'facebook' => 'Facebook',
                                    'instagram' => 'Instagram',
                                    'x' => 'X (Twitter)',
                                    'linkedin' => 'LinkedIn',
                                    'youtube' => 'YouTube',
                                    'tiktok' => 'TikTok',
                                    'whatsapp' => 'WhatsApp',
                                ])
                                ->required(),
                            TextInput::make('url')->label('الرابط')->url()->required(),
                        ])
                        ->columns(2)
                        ->addActionLabel('إضافة حساب')
                        ->default([]),
                ]),
        ]);
    }
}
