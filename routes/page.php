<?php
declare(strict_types=1);

use App\Http\Controllers\Api\V1\PageBlockController;
use App\Http\Controllers\Api\V1\PageController;
use Illuminate\Support\Facades\Route;

Route::get('pages/{slug}', [PageController::class, 'show'])->name('pages.show');

Route::get('pages/{slug}/blocks/{key}', [PageBlockController::class, 'show'])
    ->where('key', '[A-Za-z0-9_-]+')
    ->name('pages.blocks.show');

