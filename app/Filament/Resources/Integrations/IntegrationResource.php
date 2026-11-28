<?php

declare(strict_types=1);

namespace App\Filament\Resources\Integrations;

use App\Enums\ContentStatus;
use App\Filament\Resources\Integrations\Pages\CreateIntegration;
use App\Filament\Resources\Integrations\Pages\EditIntegration;
use App\Filament\Resources\Integrations\Pages\ListIntegrations;
use App\Models\Integration;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IntegrationResource extends Resource
{
    protected static ?string $model = Integration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPuzzlePiece;

    protected static ?string $modelLabel = 'تكامل';

    protected static ?string $pluralModelLabel = 'التكاملات';

    protected static ?string $navigationLabel = 'التكاملات';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->schema([
                    TextInput::make('title')->label('العنوان')->required()->maxLength(255),
                    TextInput::make('slug')
                        ->label('المعرّف (slug)')
                        ->helperText('حروف إنجليزية وشرطات، مثل: erp-accounting')
                        ->required()
                        ->alphaDash()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Textarea::make('description')->label('الوصف')->rows(3)->columnSpanFull(),
                    TagsInput::make('tags')->label('الوسوم')->helperText('اكتب الوسم واضغط Enter')->columnSpanFull(),
                    TextInput::make('icon')
                        ->label('الأيقونة (اختياري)')
                        ->helperText('اسم الأيقونة اللي الفرونت هيعرضها، مثل: calculator')
                        ->maxLength(100),
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
                TextColumn::make('title')->label('التكامل')->searchable()->limit(50),
                TextColumn::make('tags')->label('الوسوم')->badge()->color('gray'),
                TextColumn::make('status')->label('الحالة')->badge(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIntegrations::route('/'),
            'create' => CreateIntegration::route('/create'),
            'edit' => EditIntegration::route('/{record}/edit'),
        ];
    }
}
