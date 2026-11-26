<?php

declare(strict_types=1);

namespace App\Filament\Resources\DocSections\Pages;

use App\Filament\Resources\DocSections\DocSectionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDocSection extends CreateRecord
{
    protected static string $resource = DocSectionResource::class;
}
