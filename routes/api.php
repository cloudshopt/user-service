<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/info', function () {
    return response()->json([
        'ok' => true,
        'service' => config('app.name'),
        'sha' => env('IMAGE_SHA', null),
        'time' => now()->toISOString(),
    ]);
});

Route::get('/database', function () {
    try {
        $started = microtime(true);
        DB::connection()->select('SELECT 1');
        $ms = (microtime(true) - $started) * 1000;

        return response()->json([
            'ok' => true,
            'db' => [
                'connection' => DB::getDefaultConnection(),
                'database' => DB::connection()->getDatabaseName(),
                'ping_ms' => round($ms, 2),
            ],
            'time' => now()->toISOString(),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'ok' => false,
            'error' => 'DB connection failed',
            'message' => app()->hasDebugModeEnabled() ? $e->getMessage() : null,
        ], 500);
    }
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('jwt')->get('/me', [AuthController::class, 'me']);