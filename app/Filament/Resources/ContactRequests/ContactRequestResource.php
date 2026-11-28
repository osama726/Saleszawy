<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactRequests;

use App\Enums\ContactStatus;
use App\Filament\Resources\ContactRequests\Pages\EditContactRequest;
use App\Filament\Resources\ContactRequests\Pages\ListContactRequests;
use App\Models\ContactRequest;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactRequestResource extends Resource
{
    protected static ?string $model = ContactRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    protected static ?string $modelLabel = 'طلب تواصل';

    protected static ?string $pluralModelLabel = 'طلبات التواصل';

    protected static ?string $navigationLabel = 'طلبات التواصل';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 4;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = ContactRequest::query()->where('status', ContactStatus::New->value)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'info';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('بيانات الطلب')
                ->description('البيانات دي جاية من الزائر ومش بتتعدل')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->label('الاسم')->disabled(),
                    TextInput::make('phone')->label('الهاتف / الواتساب')->disabled(),
                    TextInput::make('source')->label('المصدر')->disabled(),
                    Textarea::make('message')->label('الرسالة')->rows(4)->disabled()->columnSpanFull(),
                    TextInput::make('ip_address')->label('IP')->disabled(),
                    TextInput::make('user_agent')->label('المتصفح')->disabled(),
                ]),

            Section::make('المتابعة')
                ->columns(2)
                ->schema([
                    Select::make('status')
                        ->label('الحالة')
                        ->options(ContactStatus::class)
                        ->required(),
                    Select::make('assigned_to')
                        ->label('المسؤول عن المتابعة')
                        ->relationship('assignee', 'name')
                        ->searchable()
                        ->preload(),
                    Textarea::make('notes')->label('ملاحظات داخلية')->rows(4)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('التاريخ')->dateTime('Y-m-d H:i')->sortable(),
                TextColumn::make('name')->label('الاسم')->searchable(),
                TextColumn::make('phone')->label('الهاتف')->searchable()->copyable(),
                TextColumn::make('message')->label('الرسالة')->limit(50)->placeholder('—'),
                SelectColumn::make('status')->label('الحالة')->options(ContactStatus::class),
                TextColumn::make('assignee.name')->label('المسؤول')->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('status')->label('الحالة')->options(ContactStatus::class),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContactRequests::route('/'),
            'edit' => EditContactRequest::route('/{record}/edit'),
        ];
    }
}
