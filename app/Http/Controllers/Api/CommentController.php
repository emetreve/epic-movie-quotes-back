<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Events\CommentUpdated;

class CommentController extends Controller
{
	public function store(StoreCommentRequest $request)
	{
		$comment = Comment::create($request->validated());
		event(new CommentUpdated($comment));

		return response()->json($comment, 201);
	}
}
