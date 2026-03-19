<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ExploreController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SalonController;

// Auth: OTP flow. New users are created on first successful OTP verify (phone not in users table).
Route::group(['prefix' => 'auth'], function () {
    Route::post('/otp/request', [AuthController::class, 'requestOtp']);
    Route::post('/otp/verify', [AuthController::class, 'verifyOtp']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/home', HomeController::class);
    Route::get('/explore', ExploreController::class);
    Route::get('/locations/search', [LocationController::class, 'search']);
    Route::get('/locations/{placeId}', [LocationController::class, 'show']);
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::get('/salons/{id}', [SalonController::class, 'show']);
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{salon}', [FavoriteController::class, 'destroy']);
});
