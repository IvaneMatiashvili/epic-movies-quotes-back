<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Genre extends Model
{
	use HasFactory;

	use HasTranslations;

	public $translatable = ['genre'];

	protected $guarded = [];

	public function movies()
	{
		return $this->belongsToMany(Movie::class, 'movie_genres', 'genre_id', 'movie_id');
	}
}
