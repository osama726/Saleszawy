<?php

declare(strict_types=1);

namespace App\Filament\Resources\DocArticles;

use App\Enums\ContentStatus;
use App\Filament\Resources\DocArticles\Pages\CreateDocArticle;
use App\Filament\Resources\DocArticles\Pages\EditDocArticle;
use App\Filament\Resources\DocArticles\Pages\ListDocArticles;
use App\Models\DocArticle;
use BackedEnum;
use UnitEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Unique;

class DocArticleResource extends Resource
{
    protected static ?string $model = DocArticle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $modelLabel = 'مقال توثيق';

    protected static ?string $pluralModelLabel = 'مقالات التوثيق';

    protected static ?string $navigationLabel = 'مقالات التوثيق';

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|UnitEnum|null $navigationGroup = 'التوثيق';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('بيانات المقال')
                ->columns(2)
                ->schema([
                    Select::make('section_id')
                        ->label('القسم')
                        ->relationship('section', 'title')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('title')->label('العنوان')->required()->maxLength(255),
                    TextInput::make('slug')
                        ->label('المعرّف (slug)')
                        ->helperText('فريد داخل القسم. ما تغيّروش بعد ما الفرونت يستخدمه.')
                        ->required()
                        ->alphaDash()
                        ->unique(
                            ignoreRecord: true,
                            modifyRuleUsing: fn (Unique $rule, Get $get) => $rule->where('section_id', $get('section_id')),
                        )
                        ->maxLength(255),
                    TextInput::make('version')->label('الإصدار')->maxLength(20),
                    Textarea::make('excerpt')->label('مقتطف قصير')->rows(2)->columnSpanFull(),
                    Select::make('status')
                        ->label('الحالة')
                        ->options(ContentStatus::class)
                        ->default(ContentStatus::Draft->value)
                        ->required(),
                    DateTimePicker::make('published_at')->label('تاريخ النشر'),
                ]),

            Section::make('المحتوى')
                ->schema([
                    MarkdownEditor::make('content')->label('المحتوى (Markdown)')->required()->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('المقال')->searchable()->limit(60),
                TextColumn::make('section.title')->label('القسم')->badge()->color('gray'),
                TextColumn::make('version')->label('الإصدار')->placeholder('—'),
                TextColumn::make('status')->label('الحالة')->badge(),
                TextColumn::make('updated_at')->label('آخر تعديل')->since(),
            ])
            ->filters([
                SelectFilter::make('section_id')->label('القسم')->relationship('section', 'title'),
                SelectFilter::make('status')->label('الحالة')->options(ContentStatus::class),
                TrashedFilter::make(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
            ]);
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocArticles::route('/'),
            'create' => CreateDocArticle::route('/create'),
            'edit' => EditDocArticle::route('/{record}/edit'),
        ];
    }
}
