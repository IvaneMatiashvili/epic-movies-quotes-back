<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRegistrationRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name'     => ['required', 'min:3', 'max:15', Rule::unique('users', 'name')],
			'email'    => ['required', 'email', Rule::unique('emails', 'email')],
			'password' => 'required|confirmed|min:8|max:15',
		];
	}
}
