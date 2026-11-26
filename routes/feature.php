<?php
declare(strict_types=1);

use App\Http\Controllers\Api\V1\FeatureController;
use Illuminate\Support\Facades\Route;

Route::get('features', [FeatureController::class, 'index'])->name('features.index');
