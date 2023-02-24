<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoginRequest;
use App\Models\Email;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
	public function login(StoreLoginRequest $request): jsonResponse
	{
		$attributes = $request->validated();
		$remember = false;

		if ($request['remember_me'])
		{
			$remember = true;
		}

		$email = Email::where('email', $attributes['name'])->first();
		$password = null;

		if ($email)
		{
			$user = User::where('id', $email->user_id)->first();
			$password = Hash::check($attributes['password'], $user->password);
		}

		if (!$password && Auth::attempt(['name' => $attributes['name'], 'password' => $attributes['password']], $remember))
		{
			if (auth()->user()->emails->first()->email_verified_at === null)
			{
				return response()->json(['verify_email' => 'email is not verified'], 403);
			}
			$request->session()->regenerate();
			return response()->json(auth()->user(), 200);
		}
		elseif ($password)
		{
			if ($email->email_verified_at === null)
			{
				return response()->json(['verify_email' => 'email is not verified'], 403);
			}

			Auth::loginUsingId($user->id, $remember);
			$request->session()->regenerate();
			return response()->json($user, 200);
		}
		throw ValidationException::withMessages([
			'user_does_not_exist'     => 'credentials does not exist',
		]);
	}

	public function logOut(): JsonResponse
	{
		request()->session()->invalidate();
		request()->session()->regenerateToken();

		return response()->json(['success' => 'user logged out successfully'], 200);
	}
}
