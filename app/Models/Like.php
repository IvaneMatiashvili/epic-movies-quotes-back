<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
	use HasFactory;

	protected $guarded = [];

	protected $with = ['user'];

	public function quote()
	{
		return $this->belongsTo(Quote::class);
	}

	public function notification()
	{
		return $this->morphOne(Notification::class, 'notificatable');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public static function boot()
	{
		parent::boot();

		static::deleting(function ($like) {
			$like->notification()->delete();
		});
	}
}
