<?php

declare(strict_types=1);

namespace App\Filament\Resources\FeatureCategories\Pages;

use App\Filament\Resources\FeatureCategories\FeatureCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeatureCategory extends CreateRecord
{
    protected static string $resource = FeatureCategoryResource::class;
}
