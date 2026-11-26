<?php

declare(strict_types=1);

namespace App\Filament\Resources\FeatureCategories;

use App\Filament\Resources\FeatureCategories\Pages\CreateFeatureCategory;
use App\Filament\Resources\FeatureCategories\Pages\EditFeatureCategory;
use App\Filament\Resources\FeatureCategories\Pages\ListFeatureCategories;
use App\Models\FeatureCategory;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FeatureCategoryResource extends Resource
{
    protected static ?string $model = FeatureCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $modelLabel = 'تصنيف ميزة';

    protected static ?string $pluralModelLabel = 'تصنيفات المميزات';

    protected static ?string $navigationLabel = 'تصنيفات المميزات';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('بيانات التصنيف')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->label('الاسم')->required()->maxLength(255),
                    TextInput::make('slug')
                        ->label('المعرّف (slug)')
                        ->required()
                        ->alphaDash()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    TextInput::make('group')
                        ->label('المجموعة (group)')
                        ->helperText('مكان العرض: home لقسم الرئيسية أو features لصفحة المميزات.')
                        ->default('home')
                        ->maxLength(100)
                        ->datalist(['home', 'features']),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('التصنيف')->searchable(),
                TextColumn::make('slug')->label('المعرّف')->badge()->color('gray'),
                TextColumn::make('group')->label('المجموعة')->badge()->color('info')->placeholder('—'),
                TextColumn::make('features_count')->label('عدد المميزات')->counts('features'),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->label('المجموعة')
                    ->options(fn (): array => FeatureCategory::query()->whereNotNull('group')->distinct()->pluck('group', 'group')->all()),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->hidden(
                    fn (FeatureCategory $record): bool => $record->features()->exists(),
                ),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFeatureCategories::route('/'),
            'create' => CreateFeatureCategory::route('/create'),
            'edit' => EditFeatureCategory::route('/{record}/edit'),
        ];
    }
}
