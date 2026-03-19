<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExploreController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\SalonController;

// Auth: OTP flow. New users are created on first successful OTP verify (phone not in users table).
Route::group(['prefix' => 'auth'], function () {
    Route::post('/otp/request', [AuthController::class, 'requestOtp']);
    Route::post('/otp/verify', [AuthController::class, 'verifyOtp']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});


Route::get('/home', HomeController::class);

Route::get('/explore', ExploreController::class);

Route::get('/salons/{id}', [SalonController::class, 'show']);
Route::post('/appointments', [AppointmentController::class, 'store'])->middleware('auth:sanctum');

Route::get('/products', function () {
    return response()->json([
        'message' => 'Welcome to the Saloon Booker App API!',
        'version' => '1.0.0',
        'status' => 'success',
        'sample_data' => [
            'salon_count' => 10,
            'active_users' => 25,
            'features' => [
                'booking' => true,
                'reviews' => true,
                'search' => true,
                'offers' => false,
            ],
        ],
    ]);
});



