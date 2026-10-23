<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('/catalog', [CatalogController::class, 'index']);
Route::get('/product/{slug}', [ProductController::class, 'show']);
