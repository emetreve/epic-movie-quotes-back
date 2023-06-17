<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Events\CommentUpdated;
use App\Events\NotificationUpdated;
use App\Models\Notification;

class CommentController extends Controller
{
	public function store(StoreCommentRequest $request)
	{
		$comment = Comment::create($request->validated());

		$user = Comment::find($request['quote_id'])->user;

		event(new CommentUpdated(true));

		$notification = Notification::firstOrNew([
			'end_user_id'    => $user->id,
			'user_id'        => $request['user_id'],
			'quote_id'       => $request['quote_id'],
			'comment_id'     => $comment->id,
		]);
		$notification->save();

		event(new NotificationUpdated($notification));

		return response()->json($comment, 201);
	}
}
