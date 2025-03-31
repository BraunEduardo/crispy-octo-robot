<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'store'])->middleware('api')->name('login');

Route::group([
    'middleware' => 'auth:sanctum',
], function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('gateways', GatewayController::class, [
        'except' => ['store'],
    ]);
    Route::put('gateways/{gateway}/toggle', [GatewayController::class, 'toggle'])->name('gateways.toggle');
    Route::put('gateways/{gateway}/priority', [GatewayController::class, 'changePriority'])->name('gateways.changePriority');

    Route::apiResource('users', UserController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('transactions', TransactionController::class, [
        'except' => 'update',
    ]);
});

Route::fallback(function (){
    abort(404, 'API resource not found');
});
