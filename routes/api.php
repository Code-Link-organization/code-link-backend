<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\SignupController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('user')->group(function () {
    Route::post('/signup', SignupController::class);  

    Route::group(['controller' => EmailVerificationController::class], function () {
        Route::post('/send-mail', 'sendEmail'); 
        Route::post('/check-code', 'verifyEmail');
    });


    Route::controller(LoginController::class)->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/logout', 'logout'); //auth
        });

        Route::post('/login', 'login'); 
    });

});

Route::group(['prefix'=>'user','controller'=>ResetPasswordController::class],function(){
    Route::post('/check-email','checkEmail');
    Route::post('/reset-password','resetPassword')-> middleware('auth:sanctum'); //auth
});