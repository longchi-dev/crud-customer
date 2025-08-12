<?php

use App\Http\Controllers\Customer\CustomerController;
use Illuminate\Support\Facades\Route;

Route::post('/customers', [CustomerController::class, 'create']);
Route::get('/customers', [CustomerController::class, 'getAll']);
Route::get('/customers/{id}', [CustomerController::class, 'getById']);
Route::put('/customers/{id}', [CustomerController::class, 'update']);
Route::delete('/customers/{id}', [CustomerController::class, 'delete']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
    Route::get('profile', [\App\Http\Controllers\Auth\AuthController::class, 'profile']);
    Route::post('logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
    Route::post('refresh', [\App\Http\Controllers\Auth\AuthController::class, 'refresh']);
});

