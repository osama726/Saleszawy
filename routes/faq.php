<?php
declare(strict_types=1);

use App\Http\Controllers\Api\V1\FaqController;
use Illuminate\Support\Facades\Route;

    Route::get('faqs', [FaqController::class, 'index'])->name('faqs.index');
