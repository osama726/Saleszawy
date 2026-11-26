<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware('throttle:60,1')
    ->name('api.v1.')
    ->group(function (): void {
        require __DIR__ . '/page.php';
        require __DIR__ . '/layout.php';
        require __DIR__ . '/plan.php';
        require __DIR__ . '/partner.php';
        require __DIR__ . '/faq.php';
        require __DIR__ . '/feature.php';
        require __DIR__ . '/doc.php';
    });
