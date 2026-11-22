<?php
use App\Http\Controllers\Api\V1\PageController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware('throttle:60,1')
    ->name('api.v1.')
    ->group(function (): void {
        Route::get('pages/{slug}', [PageController::class, 'show'])->name('pages.show');
    });
