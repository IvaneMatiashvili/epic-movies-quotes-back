<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegistrationRequest;
use App\Mail\VerifyEmail;
use App\Models\Email;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class RegisterController extends Controller
{
	public function register(StoreRegistrationRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$locale = request()->input('locale');

		$locale === 'en' ? App::setLocale('en') : App::setLocale('ka');

		$user = User::create([
			'name'     => $validated['name'],
			'password' => $validated['password'],
		]);

		$email = $user->emails()->create(['email' => $validated['email'], 'user_id' => $user->id]);

		$url = URL::temporarySignedRoute(
			'verify.email',
			now()->addMinutes(60 * 24),
			[
				'paramId' => $email->id,
			]
		);
		Mail::to($email)->send(new VerifyEmail($email->email, $url, $user, $locale));

		return response()->json([$user, $email], 201);
	}

	public function verifyEmail(): JsonResponse
	{
		$requestEmail = request()->query('paramId');
		$email = Email::where('id', $requestEmail)->first();

		if ($email->email_verified_at === null)
		{
			$email->email_verified_at = Carbon::now();
			$email->save();
			return response()->json(['success' => 'success'], 200);
		}
		return response()->json(['error' => 'forbidden'], 403);
	}
}
