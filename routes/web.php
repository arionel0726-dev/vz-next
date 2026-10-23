<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('/catalog', [CatalogController::class, 'index']);
Route::get('/product/{slug}', [ProductController::class, 'show']);

// Visual test page assembling the built components on mock data only.
Route::get('/test', function () {
    return Inertia::render('Test/Index');
});

Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/items', [CartController::class, 'store']);
Route::patch('/cart/items/{key}', [CartController::class, 'update']);
Route::delete('/cart/items/{key}', [CartController::class, 'destroy']);
Route::delete('/cart', [CartController::class, 'clear']);
