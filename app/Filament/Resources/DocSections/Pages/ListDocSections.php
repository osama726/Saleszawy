<?php

declare(strict_types=1);

namespace App\Filament\Resources\DocSections\Pages;

use App\Filament\Resources\DocSections\DocSectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocSections extends ListRecords
{
    protected static string $resource = DocSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
