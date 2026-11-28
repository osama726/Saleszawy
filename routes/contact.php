<?php
declare(strict_types=1);

use App\Http\Controllers\Api\V1\ContactRequestController;
use App\Http\Middleware\ThrottleContactRequests;
use Illuminate\Support\Facades\Route;

Route::post('contact-requests', [ContactRequestController::class, 'store'])
    ->middleware(ThrottleContactRequests::class)
    ->name('contact-requests.store');
