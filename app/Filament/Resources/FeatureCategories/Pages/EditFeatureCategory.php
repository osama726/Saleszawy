<?php

declare(strict_types=1);

namespace App\Filament\Resources\FeatureCategories\Pages;

use App\Filament\Resources\FeatureCategories\FeatureCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFeatureCategory extends EditRecord
{
    protected static string $resource = FeatureCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
