<?php
declare(strict_types=1);

use App\Http\Controllers\Api\V1\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('posts')->name('posts.')->group(function (): void {
    Route::get('/', [PostController::class, 'index'])->name('index');
    Route::get('{slug}', [PostController::class, 'show'])
        ->where('slug', '[A-Za-z0-9_-]+')
        ->name('show');
});
