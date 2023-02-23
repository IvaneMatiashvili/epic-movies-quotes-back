<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
	use HasFactory;

	protected $guarded = [];

	protected $with = ['user'];

	public function quote()
	{
		return $this->belongsTo(Quote::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function notifications()
	{
		return $this->morphMany(Notifications::class, 'commentable');
	}
}
