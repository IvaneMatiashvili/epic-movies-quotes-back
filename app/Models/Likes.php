<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
	use HasFactory;

	protected $guarded = [];

	protected $with = ['user'];

	public function quote()
	{
		return $this->belongsTo(Quote::class);
	}

 public function notifications()
 {
 	return $this->morphMany(Notifications::class, 'likeable');
 }

	public function user()
	{
		return $this->belongsTo(User::class);
	}

/*	public static function boot()
	{
		parent::boot();

		static::deleting(function ($quote) {
			$quote->notifications()->delete();
			$quote->notifications()->delete();
		});
	}*/
}
