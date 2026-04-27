<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RemoveBgController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




// Route::options('{any}', function () {
//     return response()->json([], 200);
// })->where('any', '.*');
Route::group(['prefix' => 'v1', 'middleware' => ['web']], function () {

    // auth Routes
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:auth');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:auth');

    Route::post('/password/forgot', [AuthController::class, 'forgotPassword'])->middleware('throttle:auth');
    Route::post('/password/reset', [AuthController::class, 'resetPassword'])->middleware('throttle:auth');
    Route::post('/password/resend-otp', [AuthController::class, 'resendForgotPasswordOtp'])->middleware('throttle:otp');

    // public routes 
    Route::get('/plans', [AuthController::class, 'getPlans'])->middleware('throttle:60,1');

    // ************ PROTECT THIS ROUTE *********************
    Route::post('/remove-bg', [RemoveBgController::class, 'removeBackground'])->middleware(['optional.auth', 'verify.client', 'throttle:remove-bg']);
    // ******************** END ********************

    Route::get('/bg-remove-status/{jobId}', [RemoveBgController::class, 'checkStatus'])->middleware('throttle:polling');
    Route::get('/bg_remove_count', [RemoveBgController::class, 'getRemoveBgCount'])->middleware('throttle:60,1');
    Route::post('/google-login', [AuthController::class, 'googleLogin'])->middleware('throttle:auth');
    Route::get('/download-image/{filename}', [RemoveBgController::class, 'downloadImage'])->where('filename', '.*')->middleware('throttle:download');
    // Protected Routes
    Route::middleware(['auth:sanctum'])->group(function () {

        Route::get('/user', function (Request $request) {
            return $request->user();
        })->middleware('throttle:60,1');

        Route::post('/logout', [AuthController::class, 'logout'])->middleware('throttle:auth');

        Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->middleware('throttle:otp');
        Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->middleware('throttle:otp');

        Route::get('/my-gallery', [RemoveBgController::class, 'myGallery'])
            ->middleware('throttle:30,1');

    });

});