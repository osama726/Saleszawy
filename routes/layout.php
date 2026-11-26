<?php
declare(strict_types=1);

use App\Http\Controllers\Api\V1\LayoutController;
use Illuminate\Support\Facades\Route;

Route::get('layout', [LayoutController::class, 'index'])->name('layout.index');
Route::get('layout/header', [LayoutController::class, 'header'])->name('layout.header');
Route::get('layout/footer', [LayoutController::class, 'footer'])->name('layout.footer');
