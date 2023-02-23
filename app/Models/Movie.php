<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Movie extends Model
{
	use HasFactory;

	use HasTranslations;

	public $translatable = ['title', 'director', 'description'];

	protected $guarded = [];

	protected $with = ['quotes', 'user'];

	public function genres()
	{
		return $this->belongsToMany(Genre::class, 'movie_genres', 'movie_id', 'genre_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function quotes()
	{
		return $this->hasMany(Quote::class);
	}

	public function scopeFilter($query, array $filters)
	{
		$query->when(
			$filters['search'] ?? false,
			function ($query, $search) {
				$search = trim($search);
				$query
					->where('title->en', 'LIKE', "%{$search}%")
					->orWhere('title->ka', 'LIKE', "%{$search}%");
			}
		);
	}

	public static function boot()
	{
		parent::boot();

		static::deleting(function ($movie) {
			$movie->quotes()->delete();
		});
	}
}
