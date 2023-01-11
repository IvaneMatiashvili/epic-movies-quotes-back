<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/auth/google/{stage}/{locale}', [SocialAuthController::class, 'redirectToProvider']);
Route::get('/auth/google/{stage}/{locale}/callback', [SocialAuthController::class, 'handleCallback']);
Route::get('/verify-email', [RegisterController::class, 'verifyEmail'])->name('verify.email')->middleware('signed');
Route::post('/forgot-password', [PasswordResetController::class, 'submitForgetPasswordForm']);
Route::get('/password-reset-form', [PasswordResetController::class, 'getSubmitResetPasswordForm'])->name('password.reset')->middleware('signed');
Route::post('/password-reset-form', [PasswordResetController::class, 'submitResetPasswordForm']);
