<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware('throttle:60,1')
    ->name('api.v1.')
    ->group(function (): void {
        include __DIR__ . '/page.php';
        include __DIR__ . '/layout.php';
        include __DIR__ . '/plan.php';
    });
