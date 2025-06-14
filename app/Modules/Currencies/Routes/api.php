<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\Currencies\Controllers\CurrencyController;
    

Route::apiResource('currencies', CurrencyController::class);