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
}
