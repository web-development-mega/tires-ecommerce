<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TireSearchController;

Route::prefix('tires/search')->group(function () {
    Route::get('by-vehicle', [TireSearchController::class, 'byVehicle']);
    Route::get('by-size', [TireSearchController::class, 'bySize']);
});
