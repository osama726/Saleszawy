<?php

declare(strict_types=1);

namespace App\Filament\Resources\Menus\RelationManagers;

use App\Enums\ButtonVariant;
use App\Enums\MenuItemKind;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'عناصر القائمة';

    protected static ?string $modelLabel = 'عنصر';

    protected static ?string $pluralModelLabel = 'العناصر';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('label')->label('النص')->required()->maxLength(150),
            Select::make('kind')
                ->label('النوع')
                ->options(MenuItemKind::class)
                ->default(MenuItemKind::Link->value)
                ->required()
                ->live(),
            TextInput::make('url')
                ->label('الرابط')
                ->helperText('مثال: /pricing أو /#faq أو https://wa.me/...')
                ->maxLength(2048)
                ->visible(fn (Get $get): bool => $get('kind') !== MenuItemKind::Text->value)
                ->required(fn (Get $get): bool => $get('kind') !== MenuItemKind::Text->value),
            Select::make('variant')
                ->label('شكل الزر')
                ->options(ButtonVariant::class)
                ->visible(fn (Get $get): bool => $get('kind') === MenuItemKind::Button->value),
            Select::make('parent_id')
                ->label('تابع لعنصر (قائمة منسدلة)')
                ->placeholder('عنصر رئيسي')
                ->options(fn (?Model $record): array => $this->getOwnerRecord()
                    ->items()
                    ->whereNull('parent_id')
                    ->when($record, fn ($query) => $query->whereKeyNot($record->getKey()))
                    ->pluck('label', 'id')
                    ->all()),
            Toggle::make('opens_in_new_tab')->label('يفتح في تاب جديد')->inline(false),
            Toggle::make('is_visible')->label('ظاهر')->default(true)->inline(false),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                TextColumn::make('label')->label('النص')->searchable(),
                TextColumn::make('kind')->label('النوع')->badge(),
                TextColumn::make('url')->label('الرابط')->limit(40)->placeholder('—'),
                TextColumn::make('parent.label')->label('تابع لـ')->placeholder('—'),
                ToggleColumn::make('is_visible')->label('ظاهر'),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->headerActions([
                CreateAction::make()->slideOver(),
            ])
            ->recordActions([
                EditAction::make()->slideOver(),
                DeleteAction::make(),
            ]);
    }
}
