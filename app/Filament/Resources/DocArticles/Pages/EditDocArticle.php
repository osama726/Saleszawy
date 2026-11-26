<?php

declare(strict_types=1);

namespace App\Filament\Resources\DocArticles\Pages;

use App\Filament\Resources\DocArticles\DocArticleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditDocArticle extends EditRecord
{
    protected static string $resource = DocArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
