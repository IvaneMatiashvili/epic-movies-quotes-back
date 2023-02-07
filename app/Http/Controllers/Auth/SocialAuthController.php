<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Email;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
	public function redirectToProvider($stage, $locale): JsonResponse
	{
		$locale === 'ka' ? $currentLocale = 'ka' : $currentLocale = '';
		$stage === 'login' ? $currentStage = 'login' : $currentStage = 'register';

		return response()->json([
			'url' => Socialite::driver('google')
				->stateless()
				->redirectUrl(config('services.google.redirect') . '/' . $currentLocale . '?stage=' . $currentStage . '&from=google')
				->redirect()
				->getTargetUrl(),
		]);
	}

	public function handleCallback($stage, $locale): JsonResponse
	{
		try
		{
			$locale === 'ka' ? $currentLocale = 'ka' : $currentLocale = '';
			$stage === 'login' ? $currentStage = 'login' : $currentStage = 'register';

			config([
				'services.google.redirect' => config('services.google.redirect') . '/' . $currentLocale . '?stage=' . $currentStage . '&from=google',
			]);
			$user = Socialite::driver('google')->stateless()->user();
		}
		catch (Exception $e)
		{
			return response()->json(['error' => 'forbidden'], 403);
		}

		$existingUser = User::where('google_id', $user->id)->first();

		if ($existingUser)
		{
			Auth::loginUsingId($existingUser->id, true);
			request()->session()->regenerate();
			return response()->json($existingUser, 200);
		}

		$existingEmail = Email::where('email', $user->email)->first();
		$existingName = User::where('name', $user->name)->first();

		if ($existingEmail && $existingName)
		{
			throw ValidationException::withMessages([
				'email'                  => 'email exist',
				'name'                   => 'username exist',
				'credentials_exist'      => 'credentials exist',
			]);
		}

		if ($existingEmail)
		{
			throw ValidationException::withMessages([
				'email'     => 'email exist',
			]);
		}
		if ($existingName)
		{
			throw ValidationException::withMessages([
				'name'     => 'username exist',
			]);
		}
		$newUser = User::create([
			'name'      => $user->name,
			'password'  => $user->password,
			'google_id' => $user->id,
		]);

		$email = $newUser->emails()->create(
			[
				'email'             => $user->email,
				'user_id'           => $newUser->id,
				'email_verified_at' => Carbon::now(),
			]
		);

		Auth::loginUsingId($newUser->id, true);
		request()->session()->regenerate();
		return response()->json($newUser, 201);
	}
}
