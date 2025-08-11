<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::post('/customers', [CustomerController::class, 'create']);
Route::get('/customers', [CustomerController::class, 'getAll']);
Route::get('/customers/{id}', [CustomerController::class, 'getById']);
Route::put('/customers/{id}', [CustomerController::class, 'update']);
Route::delete('/customers/{id}', [CustomerController::class, 'delete']);

