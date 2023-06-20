<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Events\CommentUpdated;
use App\Events\NotificationUpdated;
use App\Models\Notification;
use App\Models\Quote;

class CommentController extends Controller
{
	public function store(StoreCommentRequest $request)
	{
		$comment = Comment::create($request->validated());

		$user = Quote::find($request['quote_id'])->user;

		$quote = Quote::with('movie', 'user', 'likes', 'comments.user')->find($request['quote_id']);

		event(new CommentUpdated($quote));

		if ($user->id !== (int) $request['user_id']) {
			$notification = Notification::firstOrNew([
				'end_user_id'    => $user->id,
				'user_id'        => $request['user_id'],
				'quote_id'       => $request['quote_id'],
				'comment_id'     => $comment->id,
			]);
			$notification->save();

			event(new NotificationUpdated($notification));
		}

		return response()->json($quote, 201);
	}
}
