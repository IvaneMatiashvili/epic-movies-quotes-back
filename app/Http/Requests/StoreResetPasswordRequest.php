<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResetPasswordRequest extends FormRequest
{
	public function rules()
	{
		return [
			'password' => 'required|confirmed|min:8',
		];
	}
}
