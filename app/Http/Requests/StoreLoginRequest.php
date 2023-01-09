<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoginRequest extends FormRequest
{
	public function rules()
	{
		return [
			'name'     => ['required', 'min:3'],
			'password' => 'required',
		];
	}
}
