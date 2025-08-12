<?php

use App\Http\Controllers\Customer\CustomerController;
use Illuminate\Support\Facades\Route;

Route::post('/customers', [CustomerController::class, 'create']);
Route::get('/customers', [CustomerController::class, 'getAll']);
Route::get('/customers/{id}', [CustomerController::class, 'getById']);
Route::put('/customers/{id}', [CustomerController::class, 'update']);
Route::delete('/customers/{id}', [CustomerController::class, 'delete']);

Route::controller(\App\Http\Controllers\Auth\AuthController::class)->group(function () {
    Route::prefix('auth')->group(function () {
        # Auth Routes
        Route::post('login', 'login')->name('login');

        # Auth
    });
});
