<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quote;
use Illuminate\Support\Str;

class QuoteController extends Controller
{
	public function index(Request $request)
	{
		$search = $request->query('search');
		$locale = $request->query('locale');

		if ($search) {
			if (Str::startsWith($search, '*')) {
				$customQuery = Str::substr($search, 1);

				$quotes = Quote::with('movie', 'user', 'comments.user')
				->where('body->' . $locale, 'like', '%' . $customQuery . '%')
				->orderBy('created_at', 'desc')
				->get();
			} elseif (Str::startsWith($search, '@')) {
				$customQuery = Str::substr($search, 1);

				$quotes = Quote::with('movie', 'user', 'comments.user')
				->whereHas('movie', function ($query) use ($customQuery, $locale) {
					$query->where('name->' . $locale, 'like', '%' . $customQuery . '%');
				})->orderBy('created_at', 'desc')->get();
			} else {
				$quotes = Quote::with('movie', 'user', 'comments.user')
				->where(function ($query) use ($search, $locale) {
					$query->where('body->' . $locale, 'like', '%' . $search . '%')
					->orWhereHas('movie', function ($query) use ($search, $locale) {
						$query->where('name->' . $locale, 'like', '%' . $search . '%');
					});
				})
			   ->orderBy('created_at', 'desc')
			   ->get();
			}
		} else {
			$quotes = Quote::with('movie', 'user', 'comments.user')->orderBy('created_at', 'desc')->get();
		}

		return response()->json($quotes);
	}
}
