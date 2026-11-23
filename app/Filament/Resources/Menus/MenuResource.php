<?php

declare(strict_types=1);

namespace App\Filament\Resources\Menus;

use App\Enums\MenuLocation;
use App\Filament\Resources\Menus\Pages\CreateMenu;
use App\Filament\Resources\Menus\Pages\EditMenu;
use App\Filament\Resources\Menus\Pages\ListMenus;
use App\Filament\Resources\Menus\RelationManagers\ItemsRelationManager;
use App\Models\Menu;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3;

    protected static ?string $modelLabel = 'قائمة';

    protected static ?string $pluralModelLabel = 'القوائم (Navbar / Footer)';

    protected static ?string $navigationLabel = 'القوائم';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('بيانات القائمة')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->label('العنوان (يظهر كعنوان عمود في الفوتر)')
                        ->maxLength(255),
                    TextInput::make('key')
                        ->label('المعرّف (key)')
                        ->helperText('header-links و header-actions يقرأهم الهيدر. أي قائمة فوتر جديدة بتظهر كعمود تلقائياً.')
                        ->required()
                        ->alphaDash()
                        ->unique(ignoreRecord: true)
                        ->maxLength(50),
                    Select::make('location')
                        ->label('مكان القائمة')
                        ->options(MenuLocation::class)
                        ->required(),
                    Toggle::make('is_visible')->label('ظاهرة')->default(true)->inline(false),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('القائمة')->searchable()->placeholder('—'),
                TextColumn::make('key')->label('المعرّف')->badge()->color('gray'),
                TextColumn::make('location')->label('المكان')->badge(),
                TextColumn::make('items_count')->label('عدد العناصر')->counts('items'),
                ToggleColumn::make('is_visible')->label('ظاهرة'),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}
