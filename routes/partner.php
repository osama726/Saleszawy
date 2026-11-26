<?php
declare(strict_types=1);

use App\Http\Controllers\Api\V1\PartnerController;
use Illuminate\Support\Facades\Route;

Route::get('partners', [PartnerController::class, 'index'])->name('partners.index');
