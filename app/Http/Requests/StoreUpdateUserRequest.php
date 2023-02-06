<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateUserRequest extends FormRequest
{
	public function rules()
	{
		return [
			'user_image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
			'name'       => ['min:3', 'max:15'],
			'email'      => ['email'],
			'password'   => 'confirmed|min:8|max:15',
			'emails'     => 'min:3',
		];
	}
}
