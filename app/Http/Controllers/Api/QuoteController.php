<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuoteRequest;
use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\Like;
use Illuminate\Support\Str;
use App\Events\LikeUpdated;
use App\Events\NotificationUpdated;
use App\Models\Notification;

class QuoteController extends Controller
{
	public function index(Request $request)
	{
		$search = $request->query('search');
		$locale = $request->query('locale');

		$quoteWithData = Quote::with('movie', 'user', 'likes', 'comments.user');

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

	public function like(Request $request)
	{
		$like = Like::firstOrNew(request()->only('like', 'user_id', 'quote_id'));
		if ($like->exists) {
			$like->delete();
			return response(['message' => 'like was removed']);
		}
		$like->save();

		return response(['message' => 'like was added']);
	}

	public function broadcastLike(Request $request)
	{
		event(new LikeUpdated(true));

		$user = Quote::find($request['quote_id'])->user;

		$like = Like::where('user_id', $request->input('user_id'))
		->where('quote_id', $request->input('quote_id'))
		->first();

		if ($like) {
			$notification = Notification::firstOrNew([
				'end_user_id' => $user->id,
				'user_id'     => $request['user_id'],
				'quote_id'    => $request['quote_id'],
				'like_id'     => $like->id,
			]);
			$notification->save();
			event(new NotificationUpdated($notification));
		}
	}
}
