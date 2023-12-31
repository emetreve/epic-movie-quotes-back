<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
	use HasFactory;

	protected $guarded = [];

	public $casts = [
		'body' => 'array',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function movie(): BelongsTo
	{
		return $this->belongsTo(Movie::class);
	}

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	public function likes()
	{
		return $this->hasMany(Like::class)->select(['id', 'quote_id', 'user_id', 'like', 'created_at']);
	}

	public function scopeOrderByLatest($query)
	{
		return $query->orderBy('created_at', 'desc')->orderBy('id', 'asc');
	}

	public function scopePaginateQuotes($query, $perPage, $page)
	{
		return $query->paginate($perPage, ['*'], 'page', $page);
	}

	public function scopeSearchByBody($query, $search, $locale)
	{
		return $query->where('body->' . $locale, 'like', '%' . $search . '%');
	}

	public function scopeSearchByMovieName($query, $search, $locale)
	{
		return $query->whereHas('movie', function ($query) use ($search, $locale) {
			$query->where('name->' . $locale, 'like', '%' . $search . '%');
		});
	}

	public function scopeSearchByBodyAndMovieName($query, $search, $locale)
	{
		return $query->where(function ($query) use ($search, $locale) {
			$query->searchByBody($search, $locale)
				->orWhereHas('movie', function ($query) use ($search, $locale) {
					$query->where('name->' . $locale, 'like', '%' . $search . '%');
				});
		});
	}
}
