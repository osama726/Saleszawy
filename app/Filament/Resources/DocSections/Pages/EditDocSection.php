<?php

declare(strict_types=1);

namespace App\Filament\Resources\DocSections\Pages;

use App\Filament\Resources\DocSections\DocSectionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDocSection extends EditRecord
{
    protected static string $resource = DocSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
