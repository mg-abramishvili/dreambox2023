<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('front');
});

// ADMIN
Route::get('admin', function () {
    return view('admin');
})->middleware(['auth']);

Route::prefix("admin")->middleware(['auth'])->group(function() {
    Route::get('{any}', function () {
        return view('admin');
    })->where('any', '.*');
});

require __DIR__.'/auth.php';
