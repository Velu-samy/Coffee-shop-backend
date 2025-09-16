<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\DrinksController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SnacksController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoffeeController;
use App\Http\Controllers\AuthController;


Route::view('/', 'welcome');

// CSRF token endpoint for frontend JS with proper session handling

Route::get('get-csrf-token', function() {
    return Response::json(['csrf_token' => Session::token()]);
});



// Resource routes
Route::resource('coffees', CoffeeController::class);
Route::resource('Drink', DrinksController::class);
Route::resource('Snacks', SnacksController::class);


Route::get('/get-csrf-token', [AuthController::class, 'getToken']);


// Newsletter routes - specific routes instead of resource for better control

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth.custom');

Route::resource('oder',OrderController::class);

Route::middleware('auth:api')->group(function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);
});