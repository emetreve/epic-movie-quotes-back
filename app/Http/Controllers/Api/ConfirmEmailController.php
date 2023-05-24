<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ConfirmEmailController extends Controller
{
	public function verifyEmail(Request $request)
	{
		$userId = $request->route('id');

		$user = User::find($userId);

		if (!hash_equals((string) $user->getKey(), (string) $userId)) {
			return response()->json([
				'failure' => 400,
			]);
		}

		if (!$user->email_verified_at) {
			$user->markEmailAsVerified();
		}

		return response()->json([
			'success' => 200,
		]);
	}
}
