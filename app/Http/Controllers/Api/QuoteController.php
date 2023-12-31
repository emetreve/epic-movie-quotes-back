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
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Notification;
use Illuminate\Support\Facades\Storage;

class QuoteController extends Controller
{
	public function index(Request $request)
	{
		$search = $request->query('search');
		$locale = $request->query('locale');
		$customQuery = Str::substr($search, 1);
		$page = $request->query('page', 1);

		$quoteWithData = Quote::with('movie', 'user', 'likes', 'comments.user');

		if ($search) {
			if (Str::startsWith($search, '*')) {
				$quotes = $quoteWithData->searchByBody($customQuery, $locale);
			} elseif (Str::startsWith($search, '@')) {
				$quotes = $quoteWithData->searchByMovieName($customQuery, $locale);
			} elseif ($search === '') {
				$quotes = $quoteWithData;
			} else {
				$quotes = $quoteWithData->searchByBodyAndMovieName($search, $locale);
			}
		} else {
			$quotes = $quoteWithData;
		}

		$quotes = $quotes->orderByLatest()->paginateQuotes(5, $page);

		$paginationData = [
			'current_page' => $quotes->currentPage(),
			'last_page'    => $quotes->lastPage(),
			'per_page'     => $quotes->perPage(),
			'total'        => $quotes->total(),
		];

		return response()->json([
			'quotes'     => $quotes->items(),
			'pagination' => $paginationData,
		]);
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

		$quoteWithData = Quote::with('movie', 'user', 'likes', 'comments.user')->find($quote->id);

		return response()->json($quoteWithData, 201);
	}

	public function like(Request $request)
	{
		$like = Like::firstOrNew(request()->only('like', 'user_id', 'quote_id'));
		$quote = Quote::with('movie', 'user', 'likes', 'comments.user')->find($request['quote_id']);

		if ($like->exists) {
			$like->delete();
			$quote = Quote::with('movie', 'user', 'likes', 'comments.user')->find($request['quote_id']);
			event(new LikeUpdated($quote));
			return response()->json($quote, 201);
		}
		$like->save();

		event(new LikeUpdated($quote));

		$user = Quote::find($request['quote_id'])->user;

		if ($like && ($user->id !== (int) $request['user_id'])) {
			$notification = Notification::firstOrNew([
				'end_user_id' => $user->id,
				'user_id'     => $request['user_id'],
				'quote_id'    => $request['quote_id'],
				'like_id'     => $like->id,
			]);
			$notification->save();
			event(new NotificationUpdated($notification));
		}

		return response()->json($quote, 201);
	}

	public function destroy($id)
	{
		$quote = Quote::find($id);

		if ($quote) {
			$quote->delete();
			return response()->json(['message' => 'Quote deleted successfully'], 200);
		} else {
			return response()->json(['error' => 'Quote not found'], 404);
		}
	}

	public function get($id)
	{
		$quote = Quote::with('movie', 'user', 'likes', 'comments.user')->find($id);

		if (!$quote) {
			return response()->json(['error' => 'Quote not found'], 404);
		}

		$fullImagePath = preg_replace('/storage\//', '', Storage::url($quote->image), 1);

		$quoteWithFullUrl = $quote->toArray();
		$quoteWithFullUrl['full_url'] = $fullImagePath;

		return response()->json($quoteWithFullUrl);
	}

	public function update(UpdateQuoteRequest $request, $id)
	{
		$quote = Quote::find($id);

		if ($request->file('image')) {
			$quote->image = '/storage/' . $request->file('image')->store('quotes');
		}

		$quote->update([
			'body' => json_decode($request->input('body'), true),
		]);

		$quote->save();

		return response()->json($quote);
	}
}
