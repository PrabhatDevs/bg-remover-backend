<?php

use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    // public routes
    // Route::middleware(['web'])->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
        Route::post('/password/reset', [AuthController::class, 'resetPassword']);
        Route::post('/password/resend-otp', [AuthController::class, 'resendForgotPasswordOtp']);
        Route::get('/plans', [AuthController::class, 'getPlans']);
    // });

    Route::middleware('auth:sanctum')->group(function () {
        // test route
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        // Protected Routes
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
        Route::post('/resend-otp', [AuthController::class, 'resendOtp']);







        });
});
