<?php

declare(strict_types=1);

namespace App\Filament\Resources\Plans;

use App\Enums\ContentStatus;
use App\Filament\Resources\Plans\Pages\CreatePlan;
use App\Filament\Resources\Plans\Pages\EditPlan;
use App\Filament\Resources\Plans\Pages\ListPlans;
use App\Models\Plan;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static ?string $modelLabel = 'باقة';

    protected static ?string $pluralModelLabel = 'الباقات';

    protected static ?string $navigationLabel = 'الباقات';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('البيانات الأساسية')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->label('اسم الباقة')->required()->maxLength(255),
                    TextInput::make('slug')
                        ->label('المعرّف (slug)')
                        ->helperText('حروف إنجليزية وشرطات، مثل: diamond')
                        ->required()
                        ->alphaDash()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Textarea::make('description')->label('الوصف')->rows(2)->columnSpanFull(),
                    Select::make('status')
                        ->label('الحالة')
                        ->options(ContentStatus::class)
                        ->default(ContentStatus::Draft->value)
                        ->required(),
                    Toggle::make('is_featured')->label('باقة مميزة (الأكثر طلباً)')->inline(false),
                    TextInput::make('badge')->label('شارة الباقة المميزة')->maxLength(100)->columnSpanFull(),
                ]),

            Section::make('الأسعار')
                ->columns(2)
                ->schema([
                    TextInput::make('price_monthly')->label('السعر الشهري')->numeric()->minValue(0)->required()->default(0),
                    TextInput::make('price_yearly')->label('السعر السنوي')->numeric()->minValue(0),
                    Select::make('currency')
                        ->label('العملة')
                        ->options(['EGP' => 'جنيه مصري (ج.م)', 'SAR' => 'ريال سعودي (ر.س)', 'USD' => 'دولار (USD)'])
                        ->default('EGP')
                        ->required(),
                    TextInput::make('trial_days')->label('أيام التجربة المجانية')->numeric()->minValue(0)->default(0),
                ]),

            Section::make('الحدود')
                ->columns(2)
                ->schema([
                    TextInput::make('max_sales_users')->label('أقصى عدد موظفي مبيعات')->numeric()->minValue(1),
                    TextInput::make('max_leads')->label('أقصى عدد عملاء محتملين')->numeric()->minValue(1),
                ]),

            Section::make('المميزات')
                ->schema([
                    Repeater::make('features')
                        ->label('قائمة المميزات')
                        ->simple(TextInput::make('text')->required())
                        ->addActionLabel('إضافة ميزة')
                        ->reorderable(),
                ]),

            Section::make('زر الاشتراك')
                ->columns(2)
                ->schema([
                    TextInput::make('cta_label')->label('نص الزر')->maxLength(100),
                    TextInput::make('cta_url')->label('الرابط')->maxLength(2048),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('الباقة')->searchable(),
                TextColumn::make('price_monthly')->label('شهرياً')->numeric(decimalPlaces: 0),
                TextColumn::make('price_yearly')->label('سنوياً')->numeric(decimalPlaces: 0),
                TextColumn::make('currency')->label('العملة'),
                IconColumn::make('is_featured')->label('مميزة')->boolean(),
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
            'index' => ListPlans::route('/'),
            'create' => CreatePlan::route('/create'),
            'edit' => EditPlan::route('/{record}/edit'),
        ];
    }
}
