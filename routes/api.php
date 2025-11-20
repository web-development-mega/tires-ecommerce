<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TireSearchController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\OrderPaymentController;
use App\Http\Controllers\Api\WompiWebhookController;
use App\Http\Controllers\Api\ServiceLocationController;


Route::prefix('tires/search')->group(function () {
    Route::get('by-vehicle', [TireSearchController::class, 'byVehicle']);
    Route::get('by-size', [TireSearchController::class, 'bySize']);
});

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'show']);
    Route::post('items', [CartController::class, 'addItem']);
    Route::put('items/{item}', [CartController::class, 'updateItem']);
    Route::delete('items/{item}', [CartController::class, 'removeItem']);
});

Route::post('checkout', [CheckoutController::class, 'store']);

Route::post('orders/{order}/payments', [OrderPaymentController::class, 'store']);
Route::post('payments/wompi/webhook', [WompiWebhookController::class, 'handle']);

Route::prefix('service-locations')->group(function () {
    Route::get('/', [ServiceLocationController::class, 'index']);
    Route::get('{serviceLocation}', [ServiceLocationController::class, 'show']);
});
