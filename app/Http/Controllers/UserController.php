<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewEmailRequest;
use App\Http\Requests\StoreUpdateUserRequest;
use App\Mail\VerifyEmail;
use App\Models\Email;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
	public function get(): JsonResponse
	{
		$user = auth()->user();
		return response()->json($user, 200);
	}

	public function update(StoreUpdateUserRequest $updateRequest): JsonResponse
	{
		$request = $updateRequest->validated();

		$user = auth()->user();
		if (isset($request['name']))
		{
			if ($user->name !== $request['name'] && User::where('name', $request['name'])->exists())
			{
				throw ValidationException::withMessages([
					'user_exist' => 'user exist',
				]);
			}
		}
		if (isset($request['user_image']))
		{
			$filePath = $updateRequest->file('user_image')->store('user');

			$user->update([
				'user_image' => Storage::url($filePath),
			]);
		}

		if (isset($request['emails']))
		{
			$removedEmails = json_decode($request['emails'], true);

			collect($removedEmails)->each(function ($email) {
				Email::where('email', $email)->delete();
			});
		}
		if (isset($request['password']))
		{
			$user->update(['password' => Hash::make($request['password'])]);
		}

		if (isset($request['name']))
		{
			$user->update([
				'name' => $request['name'],
			]);
		}

		if (isset($request['email']))
		{
			$user->emails()->where('primary_email', 1)->update(['primary_email' => false]);
			$user->emails()->where('email', $request['email'])->update(['primary_email' => true]);
		}

		return response()->json($user, 200);
	}

	public function createNewEmail(StoreNewEmailRequest $request): JsonResponse
	{
		$user = auth()->user();
		$validated = $request->validated();
		$locale = request()->input('locale');

		$email = $user->emails()->create(['email' => $validated['email'], 'user_id' => $user->id]);

		$url = URL::temporarySignedRoute(
			'verify.email',
			now()->addMinutes(60 * 24),
			[
				'paramId' => $email->id,
			]
		);

		Mail::to($email)->send(new VerifyEmail($email->email, $url, $user, $locale));

		return response()->json($email->user, 201);
	}
}
