<?php

declare(strict_types=1);

namespace App\Filament\Resources\DocSections;

use App\Enums\ContentStatus;
use App\Filament\Resources\DocSections\Pages\CreateDocSection;
use App\Filament\Resources\DocSections\Pages\EditDocSection;
use App\Filament\Resources\DocSections\Pages\ListDocSections;
use App\Models\DocSection;
use BackedEnum;
use UnitEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DocSectionResource extends Resource
{
    protected static ?string $model = DocSection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?string $modelLabel = 'قسم توثيق';

    protected static ?string $pluralModelLabel = 'أقسام التوثيق';

    protected static ?string $navigationLabel = 'أقسام التوثيق';

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|UnitEnum|null $navigationGroup = 'التوثيق';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('بيانات القسم')
                ->columns(2)
                ->schema([
                    TextInput::make('title')->label('العنوان')->required()->maxLength(255),
                    TextInput::make('slug')
                        ->label('المعرّف (slug)')
                        ->helperText('بيظهر في الرابط: /docs/{section}/{article}.')
                        ->required()
                        ->alphaDash()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Select::make('parent_id')
                        ->label('تابع لقسم')
                        ->placeholder('قسم رئيسي')
                        ->searchable()
                        ->options(fn (?Model $record): array => DocSection::query()
                            ->when($record, fn ($query) => $query->whereKeyNot($record->getKey()))
                            ->orderBy('title')
                            ->pluck('title', 'id')
                            ->all()),
                    TextInput::make('icon')->label('الأيقونة (اسم)')->maxLength(100),
                    Select::make('status')
                        ->label('الحالة')
                        ->options(ContentStatus::class)
                        ->default(ContentStatus::Draft->value)
                        ->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('القسم')->searchable(),
                TextColumn::make('slug')->label('المعرّف')->badge()->color('gray'),
                TextColumn::make('parent.title')->label('تابع لـ')->placeholder('—'),
                TextColumn::make('articles_count')->label('عدد المقالات')->counts('articles'),
                TextColumn::make('status')->label('الحالة')->badge(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
                // The FK is RESTRICT: a section that still has articles (even trashed) can't be deleted.
                DeleteAction::make()->hidden(
                    fn (DocSection $record): bool => $record->articles()->withTrashed()->exists(),
                ),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocSections::route('/'),
            'create' => CreateDocSection::route('/create'),
            'edit' => EditDocSection::route('/{record}/edit'),
        ];
    }
}
