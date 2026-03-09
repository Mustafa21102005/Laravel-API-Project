<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\Api\AuthController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('resend/code', [AuthController::class, 'resendActivatonCode']);
    Route::post('activate/account', [AuthController::class, 'activateAccount']);
});
