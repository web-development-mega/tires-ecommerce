<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\TireCatalogController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::prefix('tires')->group(function () {
    Route::get('/', [TireCatalogController::class, 'index'])
        ->name('tires.index');

    Route::get('{slug}', [TireCatalogController::class, 'show'])
        ->name('tires.show');
});

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/', [CartController::class, 'store'])
        ->name('cart.store');

    Route::patch('{item}', [CartController::class, 'update'])
        ->name('cart.update');

    Route::delete('{item}', [CartController::class, 'destroy'])
        ->name('cart.destroy');
});

Route::prefix('checkout')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/', [CheckoutController::class, 'store'])
        ->name('checkout.store');
});
