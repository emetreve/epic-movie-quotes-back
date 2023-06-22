<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
	use HasFactory;

	public $casts = [
		'name'        => 'array',
		'description' => 'array',
		'director'    => 'array',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function quotes(): HasMany
	{
		return $this->hasMany(Quote::class);
	}

	public function genres()
	{
		return $this->belongsToMany(Genre::class, 'genre_movie');
	}

    public function scopeSearchByName($query, $search, $locale)
	{
		return $query->where('name->' . $locale, 'like', '%' . $search . '%');
	}
}
