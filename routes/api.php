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
	Route::post('/edit-user-information', [UserController::class, 'update']);
	Route::get('/get-user-information', [UserController::class, 'get']);
	Route::post('/create-new-email', [UserController::class, 'createNewEmail']);
	Route::get('/get-genres', [MovieController::class, 'getGenres']);
	Route::get('/get-movies', [MovieController::class, 'index']);
	Route::get('/get-movie', [MovieController::class, 'show']);
	Route::get('/get-quote', [QuoteController::class, 'show']);
	Route::get('/get-quotes', [QuoteController::class, 'index']);
	Route::post('/search-movies', [MovieController::class, 'search']);
	Route::post('/store-movie', [MovieController::class, 'store']);
	Route::post('/edit-movie', [MovieController::class, 'edit']);
	Route::post('/delete-movie', [MovieController::class, 'delete']);
	Route::post('/store-quote', [QuoteController::class, 'store']);
	Route::post('/edit-quote', [QuoteController::class, 'edit']);
	Route::post('/delete-quote', [QuoteController::class, 'delete']);
	Route::post('/store-comment', [QuoteController::class, 'storeComment']);
	Route::post('/store-likes', [LikeController::class, 'storeOrDeleteLike']);
	Route::post('/search-quotes', [QuoteController::class, 'search']);
	Route::get('/get-notifications', [NotificationController::class, 'index']);
	Route::get('/remove-notifications', [NotificationController::class, 'deleteAll']);
	Route::post('/remove-notification', [NotificationController::class, 'delete']);
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/auth/google/{stage}/{locale}', [SocialAuthController::class, 'redirectToProvider']);
Route::get('/auth/google/{stage}/{locale}/callback', [SocialAuthController::class, 'handleCallback']);
Route::get('/verify-email', [RegisterController::class, 'verifyEmail'])->name('verify.email')->middleware('signed');
Route::post('/forgot-password', [PasswordResetController::class, 'submitForgetPasswordForm']);
Route::get('/password-reset-form', [PasswordResetController::class, 'getSubmitResetPasswordForm'])->name('password.reset')->middleware('signed');
Route::post('/password-reset-form', [PasswordResetController::class, 'submitResetPasswordForm']);
