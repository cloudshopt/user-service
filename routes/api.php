<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('health'));

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
})->name('health');
