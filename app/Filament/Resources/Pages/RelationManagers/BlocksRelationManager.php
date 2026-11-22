<?php

declare(strict_types=1);

namespace App\Filament\Resources\Pages\RelationManagers;

use App\Enums\BlockType;
use App\Filament\Blocks\BlockRegistry;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class BlocksRelationManager extends RelationManager
{
    protected static string $relationship = 'blocks';

    protected static ?string $title = 'أقسام الصفحة';

    protected static ?string $modelLabel = 'قسم';

    protected static ?string $pluralModelLabel = 'الأقسام';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('type')
                ->label('نوع القسم')
                ->options(BlockType::class)
                ->required()
                ->live()
                ->disabledOn('edit'),
            Toggle::make('is_visible')
                ->label('ظاهر في الموقع')
                ->default(true),
            // The fields change with the selected type; values are stored in the `data` JSON column.
            Group::make()
                ->statePath('data')
                ->columns(2)
                ->columnSpanFull()
                ->schema(fn (Get $get): array => BlockRegistry::fields($get('type'))),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                TextColumn::make('type')->label('القسم')->badge(),
                TextColumn::make('data.title')->label('العنوان')->limit(60)->placeholder('—'),
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
