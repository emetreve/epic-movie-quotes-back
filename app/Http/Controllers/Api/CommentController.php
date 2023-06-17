<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Events\CommentUpdated;
use Illuminate\Http\Request;
use App\Models\Quote;
use App\Events\NotificationUpdated;
use App\Models\Notification;

class CommentController extends Controller
{
	public function store(StoreCommentRequest $request)
	{
		$comment = Comment::create($request->validated());

		return response()->json($comment, 201);
	}

	public function broadcastComment(Request $request)
	{
		event(new CommentUpdated(true));

		$user = Quote::find($request['quote_id'])->user;

		$comment = Comment::where('user_id', $request->input('user_id'))
		->where('quote_id', $request->input('quote_id'))
		->first();

		if ($comment) {
			$notification = Notification::firstOrNew([
				'end_user_id'    => $user->id,
				'user_id'        => $request['user_id'],
				'quote_id'       => $request['quote_id'],
				'comment_id'     => $comment->id,
			]);
			$notification->save();
			event(new NotificationUpdated($notification));
		}
	}
}
