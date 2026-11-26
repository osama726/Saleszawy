<?php
declare(strict_types=1);

use App\Http\Controllers\Api\V1\DocController;
use Illuminate\Support\Facades\Route;

Route::prefix('docs')->name('docs.')->group(function (): void {
    Route::get('tree', [DocController::class, 'tree'])->name('tree');
    Route::get('search', [DocController::class, 'search'])->name('search');

    Route::get('{section}/{article}', [DocController::class, 'show'])
        ->where(['section' => '[A-Za-z0-9_-]+', 'article' => '[A-Za-z0-9_-]+'])
        ->name('articles.show');
});
