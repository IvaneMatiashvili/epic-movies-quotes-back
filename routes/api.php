<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::middleware('auth:sanctum')->group(function () {
	Route::post('/logout', [LoginController::class, 'logOut']);

	Route::controller(UserController::class)->group(function () {
		Route::post('/edit-user-information', 'update');
		Route::get('/get-user-information', 'get');
		Route::post('/create-new-email', 'createNewEmail');
	});
	Route::controller(MovieController::class)->group(function () {
		Route::get('/get-genres', 'getGenres');
		Route::get('/get-movies', 'index');
		Route::get('/get-movie', 'show');
		Route::post('/search-movies', 'search');
		Route::post('/store-movie', 'store');
		Route::post('/edit-movie', 'edit');
		Route::post('/delete-movie', 'delete');
	});
	Route::controller(QuoteController::class)->group(function () {
		Route::get('/get-quote', 'show');
		Route::get('/get-quotes', 'index');
		Route::post('/store-quote', 'store');
		Route::post('/edit-quote', 'edit');
		Route::post('/delete-quote', 'delete');
		Route::post('/store-comment', 'storeComment');
		Route::post('/search-quotes', 'search');
	});
	Route::post('/store-likes', [LikeController::class, 'storeOrDeleteLike']);

	Route::controller(NotificationController::class)->group(function () {
		Route::get('/get-notifications', 'index');
		Route::get('/remove-notifications', 'deleteAll');
		Route::post('/remove-notification', 'delete');
	});
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/auth/google/{stage}/{locale}', [SocialAuthController::class, 'redirectToProvider']);
Route::get('/auth/google/{stage}/{locale}/callback', [SocialAuthController::class, 'handleCallback']);
Route::get('/verify-email', [RegisterController::class, 'verifyEmail'])->name('verify.email')->middleware('signed');
Route::post('/forgot-password', [PasswordResetController::class, 'submitForgetPasswordForm']);
Route::get('/password-reset-form', [PasswordResetController::class, 'getSubmitResetPasswordForm'])->name('password.reset')->middleware('signed');
Route::post('/password-reset-form', [PasswordResetController::class, 'submitResetPasswordForm']);
