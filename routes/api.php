<?php

use App\Http\Controllers\DrinksController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CoffeeController;
use App\Http\Controllers\SnacksController;
// 🔓 Public Routes
Route::post('login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);

Route::post('/orders', [OrderController::class, 'store']);
Route::resource('/drink', DrinksController::class); // Assuming this was missing
Route::resource('snack', SnacksController::class); // Assuming this was missing

Route::resource('coffee', CoffeeController::class); // Assuming this was missing
Route::resource('news', NewsletterController::class);
// 🔐 Protected Routes (JWT required)
Route::middleware('auth:api')->group(function () {
    
    Route::resource('contact', ContactController::class);
});