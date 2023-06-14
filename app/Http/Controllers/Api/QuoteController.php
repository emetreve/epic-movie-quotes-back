<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuoteRequest;
use Illuminate\Http\Request;
use App\Models\Quote;
use Illuminate\Support\Str;

class QuoteController extends Controller
{
	public function index(Request $request)
	{
		$search = $request->query('search');
		$locale = $request->query('locale');

		$quoteWithData = Quote::with('movie', 'user', 'comments.user');

		$customQuery = Str::substr($search, 1);

		if ($search) {
			if (Str::startsWith($search, '*')) {
				$quotes = $quoteWithData->searchByBody($customQuery, $locale)
					->orderBy('created_at', 'desc')->get();
			} elseif (Str::startsWith($search, '@')) {
				$quotes = $quoteWithData->searchByMovieName($customQuery, $locale)
					->orderBy('created_at', 'desc')->get();
			} else {
				$quotes = $quoteWithData->searchByBodyAndMovieName($search, $locale)
					->orderBy('created_at', 'desc')->get();
			}
		} else {
			$quotes = $quoteWithData->orderBy('created_at', 'desc')->get();
		}

		return response()->json($quotes);
	}

	public function store(StoreQuoteRequest $request)
	{
		$quote = new Quote();

		$quote->image = '/storage/' . $request->file('image')->store('quotes');

		$quote->body = [
			'en' => $request->input('bodyEn'),
			'ka' => $request->input('bodyGe'),
		];

		$quote->movie_id = $request->input('movie_id');
		$quote->user_id = $request->input('user_id');

		$quote->save();

		return response()->json(['message' => 'Quote created successfully']);
	}
}
