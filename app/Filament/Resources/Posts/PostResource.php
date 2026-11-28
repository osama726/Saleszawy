<?php

declare(strict_types=1);

namespace App\Filament\Resources\Posts;

use App\Enums\ContentStatus;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Models\Post;
use BackedEnum;
use UnitEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?string $modelLabel = 'مقال';

    protected static ?string $pluralModelLabel = 'مقالات المدونة';

    protected static ?string $navigationLabel = 'مقالات المدونة';

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|UnitEnum|null $navigationGroup = 'المدونة';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('بيانات المقال')
                ->columns(2)
                ->schema([
                    Select::make('category_id')
                        ->label('التصنيف')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),
                    TextInput::make('title')->label('العنوان')->required()->maxLength(255),
                    TextInput::make('slug')
                        ->label('المعرّف (slug)')
                        ->required()
                        ->alphaDash()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    TextInput::make('reading_time')
                        ->label('مدة القراءة (دقائق)')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(120),
                    TextInput::make('author_name')->label('اسم الكاتب')->maxLength(255),
                    TextInput::make('author_title')->label('صفة الكاتب')->maxLength(255),
                    TagsInput::make('tags')->label('الوسوم')->columnSpanFull(),
                    Textarea::make('excerpt')->label('مقتطف قصير')->rows(3)->columnSpanFull(),
                    SpatieMediaLibraryFileUpload::make('cover')
                        ->label('صورة الغلاف')
                        ->collection('cover')
                        ->image()
                        ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                        ->maxSize(4096)
                        ->columnSpanFull(),
                    Select::make('status')
                        ->label('الحالة')
                        ->options(ContentStatus::class)
                        ->default(ContentStatus::Draft->value)
                        ->required(),
                    DateTimePicker::make('published_at')->label('تاريخ النشر'),
                    Toggle::make('is_featured')->label('مقال مميز')->default(false),
                ]),

            Section::make('المحتوى')
                ->schema([
                    MarkdownEditor::make('content')->label('المحتوى (Markdown)')->required()->columnSpanFull(),
                ]),

            Section::make('SEO')
                ->columns(2)
                ->collapsed()
                ->schema([
                    TextInput::make('seo_title')->label('عنوان SEO')->maxLength(255),
                    Textarea::make('seo_description')->label('وصف SEO')->rows(2)->maxLength(320)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover')->label('الغلاف')->collection('cover'),
                TextColumn::make('title')->label('المقال')->searchable()->limit(55),
                TextColumn::make('category.name')->label('التصنيف')->badge()->color('gray')->placeholder('—'),
                IconColumn::make('is_featured')->label('مميز')->boolean(),
                TextColumn::make('status')->label('الحالة')->badge(),
                TextColumn::make('published_at')->label('تاريخ النشر')->date()->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')->label('التصنيف')->relationship('category', 'name'),
                SelectFilter::make('status')->label('الحالة')->options(ContentStatus::class),
                TrashedFilter::make(),
            ])
            ->defaultSort('published_at', 'desc')
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
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
