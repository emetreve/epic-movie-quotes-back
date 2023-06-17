<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $with = ['user', 'comment'];

    protected $guarded = [];

    public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function comment()
	{
		return $this->belongsTo(Comment::class);
	}
}
