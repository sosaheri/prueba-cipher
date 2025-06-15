<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    require __DIR__.'/auth.php';
});

Route::get('/health', function() {
    return response()->json(['status' => 'API esta arriba']);
});

Route::prefix('currencies')->group(function () {
    require __DIR__.'/../app/Modules/Currencies/Routes/api.php';
});

Route::prefix('products')->group(function () {
    require __DIR__.'/../app/Modules/Products/Routes/api.php';
});


