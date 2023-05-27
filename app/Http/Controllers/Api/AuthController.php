<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
	public function signup(SignUpRequest $request): JsonResponse
	{
		$credentials = $request->validated();
		$credentials['password'] = bcrypt($credentials['password']);

		$user = User::create($credentials);
		Auth::loginUsingId($user->id);

		event(new Registered($user));

		return response()->json([
			'success' => 200,
		]);
	}

	public function resendEmailLink(Request $request)
	{
		$userId = $request->id;
		$user = User::find($userId);

		if (!$user) {
			return response()->json([
				'error' => 'User not found',
			], 400);
		}

		$user->updated_at = Carbon::now();
		$user->save();

		$user->sendEmailVerificationNotification();

		return response()->json([
			'success' => 200,
		]);
	}
}
