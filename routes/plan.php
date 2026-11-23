<?php

use App\Http\Controllers\Api\V1\PlanController;
use Illuminate\Support\Facades\Route;

Route::get('plans', [PlanController::class, 'index'])->name('plans.index');
