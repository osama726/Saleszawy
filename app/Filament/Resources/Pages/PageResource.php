<?php

declare(strict_types=1);

namespace App\Filament\Resources\Pages;

use App\Enums\ContentStatus;
use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Filament\Resources\Pages\RelationManagers\BlocksRelationManager;
use App\Models\Page;
use BackedEnum;
use UnitEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $modelLabel = 'صفحة';

    protected static ?string $pluralModelLabel = 'الصفحات';

    protected static ?string $navigationLabel = 'الصفحات';

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|UnitEnum|null $navigationGroup = 'إدارة الموقع';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('بيانات الصفحة')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->label('العنوان')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('slug')
                        ->label('الرابط (slug)')
                        ->required()
                        ->alphaDash()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Select::make('status')
                        ->label('الحالة')
                        ->options(ContentStatus::class)
                        ->default(ContentStatus::Draft->value)
                        ->required(),
                    DateTimePicker::make('published_at')
                        ->label('تاريخ النشر'),
                ]),
            Section::make('SEO')
                ->schema([
                    TextInput::make('seo_title')
                        ->label('عنوان SEO')
                        ->maxLength(255),
                    Textarea::make('seo_description')
                        ->label('وصف SEO')
                        ->rows(3)
                        ->maxLength(320),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('العنوان')->searchable(),
                TextColumn::make('slug')->label('الرابط')->badge()->color('gray'),
                TextColumn::make('status')->label('الحالة')->badge(),
                TextColumn::make('blocks_count')->label('عدد الأقسام')->counts('blocks'),
                TextColumn::make('updated_at')->label('آخر تعديل')->since(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('id');
    }

    public static function getRelations(): array
    {
        return [
            BlocksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}
