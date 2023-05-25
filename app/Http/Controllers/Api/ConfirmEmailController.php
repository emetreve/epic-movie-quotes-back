<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ConfirmEmailController extends Controller
{
	public function verifyEmail(Request $request): JsonResponse
	{
		$userId = $request->route('id');

		$hash = $request->route('hash');

		$user = User::find($userId);

		if (!hash_equals((string) $user->getKey(), (string) $userId)) {
			return response()->json([
				'failure' => 400,
			], 400);
		}

		if (!hash_equals(sha1($user->email), (string) $hash)) {
			return response()->json([
				'hash_failure' => 400,
			], 400);
		}

		if (!$user->email_verified_at) {
			$user->markEmailAsVerified();
		}

		return response()->json([
			'success' => 200,
		], 200);
	}
}
