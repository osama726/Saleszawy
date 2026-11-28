<?php
declare(strict_types=1);

use App\Http\Controllers\Api\V1\IntegrationController;
use Illuminate\Support\Facades\Route;


Route::get('integrations', [IntegrationController::class, 'index'])->name('integrations.index');
