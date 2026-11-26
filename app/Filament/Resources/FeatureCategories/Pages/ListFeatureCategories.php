<?php

declare(strict_types=1);

namespace App\Filament\Resources\FeatureCategories\Pages;

use App\Filament\Resources\FeatureCategories\FeatureCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeatureCategories extends ListRecords
{
    protected static string $resource = FeatureCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
