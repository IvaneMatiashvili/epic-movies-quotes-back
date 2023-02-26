<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmailRequest;
use App\Http\Requests\StoreResetPasswordRequest;
use App\Mail\PasswordResetEmail;
use App\Models\Email;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
	public function submitForgetPasswordForm(StoreEmailRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$locale = request()->input('locale');

		$locale === 'en' ? App::setLocale('en') : App::setLocale('ka');

		$requestEmail = $validated['email'];
		$email = Email::where('email', $requestEmail)->first();
		$user = $email->user;

		if ($user->google_id !== null)
		{
			throw ValidationException::withMessages([
				'google_email' => 'access denied',
			]);
		}

		if ($email->email_verified_at === null)
		{
			return response()->json(['verify_email' => 'email is not verified'], 403);
		}

		$url = URL::temporarySignedRoute(
			'password.reset',
			now()->addMinutes(60 * 24),
			[
				'paramId' => $email->id,
			]
		);
		Mail::to($email)->send(new PasswordResetEmail($email->email, $url, $user, $locale));

		return response()->json($email, 200);
	}

	public function getSubmitResetPasswordForm(): JsonResponse
	{
		if (!request()->hasValidSignature())
		{
			abort(403);
		}

		return response()->json(200);
	}

	public function submitResetPasswordForm(StoreResetPasswordRequest $request): JsonResponse
	{
		$requestEmail = $request->query('paramId');
		$email = Email::where('id', $requestEmail)->first();
		$user = $email->user;
		$user->update(['password' => Hash::make($request->password)]);
		return response()->json(['success' => 'password successfully updated'], 201);
	}
}
