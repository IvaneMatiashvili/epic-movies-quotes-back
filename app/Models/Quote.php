<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Quote extends Model
{
	use HasFactory;
	use HasTranslations;

	protected $guarded = [];

	protected $with = ['comments', 'likes'];

	public $translatable = ['quote', 'movie_title'];

	public function movie()
	{
		return $this->belongsTo(Movie::class);
	}

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	public function likes()
	{
		return $this->hasMany(Like::class);
	}

	public function scopeFilter($query, array $filters)
	{
		$query->when(
			$filters['search'] ?? false,
			static function ($query, $search) {
				$search = trim($search);
				$searchValue = ltrim($search, $search[0]);

				if ($search[0] === '@')
				{
					$query
						->Where('movie_title->en', 'LIKE', "%{$searchValue}%")
						->orWhere('movie_title->ka', 'LIKE', "%{$searchValue}%");
				}
				elseif ($search[0] === '#')
				{
					$query

						->Where('quote->en', 'LIKE', "%{$searchValue}%")
						->orWhere('quote->ka', 'LIKE', "%{$searchValue}%");
				}
				else
				{
					$query
						->Where('quote->en', 'LIKE', '%{quote-ka}%');
				}
			}
		);
	}

	public static function boot()
	{
		parent::boot();

		static::deleting(function ($quote) {
			$quote->comments()->delete();
			$quote->likes()->delete();
		});
	}
}
