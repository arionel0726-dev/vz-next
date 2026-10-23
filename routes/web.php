<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

// Temporary mock route for the catalog UI — no controller, no database.
Route::get('/catalog', function () {
    return Inertia::render('Catalog/Index');
});
