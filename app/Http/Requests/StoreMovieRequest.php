<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'name_en'                     => ['required', 'max:255'],
			'name_ka'                     => ['required', 'max:255'],
			'director_en'                 => ['required', 'max:255'],
			'director_ka'                 => ['required', 'max:255'],
			'description_en'              => ['required', 'max:600'],
			'description_ka'              => ['required', 'max:600'],
			'release_date'                => ['required'],
			'budget'                      => 'required|min:1',
			'thumbnail'                   => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
			'genres_ids'                  => 'required',
		];
	}
}
