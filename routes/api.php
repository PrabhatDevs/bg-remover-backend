<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RemoveBgController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::group(['prefix' => 'v1', 'middleware' => ['web']], function () {

    // Public Routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
    Route::post('/password/resend-otp', [AuthController::class, 'resendForgotPasswordOtp']);
    Route::get('/plans', [AuthController::class, 'getPlans']);
    Route::post('/remove-bg', [RemoveBgController::class, 'removeBackground'])->middleware('throttle:remove-bg');
    Route::get('/bg_remove_count', [RemoveBgController::class, 'getRemoveBgCount']);
    // Protected Routes
    Route::get('/download-image/{filename}', [RemoveBgController::class, 'downloadImage'])
        ->where('filename', '.*');
    Route::middleware(['auth:sanctum'])->group(function () {

        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
        Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
        Route::get('/my-gallery', [RemoveBgController::class, 'myGallery']);

    });

});