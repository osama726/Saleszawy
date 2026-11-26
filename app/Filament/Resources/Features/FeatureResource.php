<?php

declare(strict_types=1);

namespace App\Filament\Resources\Features;

use App\Enums\ContentStatus;
use App\Filament\Resources\Features\Pages\CreateFeature;
use App\Filament\Resources\Features\Pages\EditFeature;
use App\Filament\Resources\Features\Pages\ListFeatures;
use App\Models\Feature;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FeatureResource extends Resource
{
    protected static ?string $model = Feature::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?string $modelLabel = 'ميزة';

    protected static ?string $pluralModelLabel = 'المميزات';

    protected static ?string $navigationLabel = 'المميزات';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('البيانات الأساسية')
                ->columns(2)
                ->schema([
                    Select::make('category_id')
                        ->label('التصنيف')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('group')
                        ->label('المجموعة (group)')
                        ->helperText('مكان العرض: home لقسم الرئيسية أو features لصفحة المميزات.')
                        ->default('home')
                        ->maxLength(100)
                        ->datalist(['home', 'features']),
                    TextInput::make('title')->label('العنوان')->required()->maxLength(255),
                    TextInput::make('slug')
                        ->label('المعرّف (slug)')
                        ->required()
                        ->alphaDash()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    TextInput::make('summary')->label('الوصف المختصر')->maxLength(500)->columnSpanFull(),
                    TextInput::make('badge')->label('الشارة (Badge)')->maxLength(100),
                    TextInput::make('icon')->label('الأيقونة')->maxLength(100),
                    TextInput::make('cta_label')->label('نص الزر (CTA)')->maxLength(100),
                    TextInput::make('cta_url')->label('رابط الزر (CTA)')->maxLength(255),
                    Select::make('status')
                        ->label('الحالة')
                        ->options(ContentStatus::class)
                        ->default(ContentStatus::Draft->value)
                        ->required(),
                ]),

            Section::make('التفاصيل وخطوات العمل')
                ->schema([
                    Textarea::make('description')->label('الوصف التفصيلي')->rows(4)->columnSpanFull(),
                    Repeater::make('points')
                        ->label('النقاط المميزة')
                        ->simple(TextInput::make('point')->label('النقطة')->required())
                        ->defaultItems(0)
                        ->columnSpanFull(),
                    Repeater::make('how_it_works')
                        ->label('كيف تعمل (خطوات)')
                        ->schema([
                            TextInput::make('title')->label('الخطوة')->required(),
                            TextInput::make('icon')->label('الأيقونة')->maxLength(100),
                        ])
                        ->columns(2)
                        ->defaultItems(0)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('الميزة')->searchable()->limit(50),
                TextColumn::make('category.name')->label('التصنيف')->badge()->color('gray'),
                TextColumn::make('group')->label('المجموعة')->badge()->color('info')->placeholder('—'),
                TextColumn::make('badge')->label('الشارة')->badge()->color('success')->placeholder('—'),
                TextColumn::make('status')->label('الحالة')->badge(),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->label('المجموعة')
                    ->options(fn (): array => Feature::query()->whereNotNull('group')->distinct()->pluck('group', 'group')->all()),
                SelectFilter::make('category_id')->label('التصنيف')->relationship('category', 'name'),
                SelectFilter::make('status')->label('الحالة')->options(ContentStatus::class),
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
            'index' => ListFeatures::route('/'),
            'create' => CreateFeature::route('/create'),
            'edit' => EditFeature::route('/{record}/edit'),
        ];
    }
}
