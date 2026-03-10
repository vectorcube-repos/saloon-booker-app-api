<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Auth: phone + password (no email)
Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});


Route::get('/home', function () {
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