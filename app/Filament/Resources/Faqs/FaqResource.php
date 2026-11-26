<?php

declare(strict_types=1);

namespace App\Filament\Resources\Faqs;

use App\Enums\ContentStatus;
use App\Filament\Resources\Faqs\Pages\CreateFaq;
use App\Filament\Resources\Faqs\Pages\EditFaq;
use App\Filament\Resources\Faqs\Pages\ListFaqs;
use App\Models\Faq;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static ?string $modelLabel = 'سؤال';

    protected static ?string $pluralModelLabel = 'الأسئلة الشائعة';

    protected static ?string $navigationLabel = 'الأسئلة الشائعة';

    protected static ?string $recordTitleAttribute = 'question';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->schema([
                    TextInput::make('question')->label('السؤال')->required()->maxLength(500)->columnSpanFull(),
                    Textarea::make('answer')->label('الإجابة')->required()->rows(5)->columnSpanFull(),
                    TextInput::make('group')
                        ->label('المجموعة (group)')
                        ->helperText('السؤال بيظهر في البلوك اللي مجموعته نفس الاسم. مثال: home')
                        ->default('home')
                        ->maxLength(100)
                        ->datalist(fn (): array => Faq::query()->whereNotNull('group')->distinct()->pluck('group')->all()),
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
                TextColumn::make('question')->label('السؤال')->searchable()->limit(70),
                TextColumn::make('group')->label('المجموعة')->badge()->color('gray')->placeholder('—'),
                TextColumn::make('status')->label('الحالة')->badge(),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->label('المجموعة')
                    ->options(fn (): array => Faq::query()->whereNotNull('group')->distinct()->pluck('group', 'group')->all()),
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
            'index' => ListFaqs::route('/'),
            'create' => CreateFaq::route('/create'),
            'edit' => EditFaq::route('/{record}/edit'),
        ];
    }
}
