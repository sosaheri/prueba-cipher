<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\Products\Controllers\ProductController;

Route::apiResource('products', ProductController::class);

Route::prefix('products/{product}')->group(function () {
    Route::get('/prices', [ProductController::class, 'prices']);
    Route::post('/prices', [ProductController::class, 'storePrice']);
});