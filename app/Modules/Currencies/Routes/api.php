<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\Currencies\Controllers\CurrencyController;
    
Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('currencies', CurrencyController::class);

});