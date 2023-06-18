<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
	public function index()
	{
		$notifications = Notification::with('user', 'comment')->orderBy('created_at', 'desc')->get();
		return response()->json($notifications);
	}

	public function markAllRead()
	{
		$endUserId = request()->input('end_user_id');

		Notification::where('end_user_id', $endUserId)->update(['read' => true]);

		return response(['message' => 'Notifications were successfully marked as read.']);
	}

	public function markOneRead()
	{
		$id = request()->input('notification_id');

		$notification = Notification::find($id);
		$notification->update(['read' => true]);

		return response(['message' => 'Notification was successfully marked as read.']);
	}
}
