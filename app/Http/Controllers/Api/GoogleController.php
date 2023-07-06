<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class GoogleController extends Controller
{
	public function redirect(): JsonResponse
	{
		$url = Socialite::driver('google')->redirect()->getTargetUrl();
		return response()->json(['url' => $url]);
	}

	public function callback(Request $request): JsonResponse
	{
		$incomingUser = Socialite::driver('google')->stateless()->user();

		$user = User::where('email', $incomingUser->email)->where('is_google_user', 1)->first();

		$userWithoutGoogleAccount = User::where('email', $incomingUser->email)->where('is_google_user', null)->first();

		if (!$user && !$userWithoutGoogleAccount) {
			$user = User::create([
				'name'          => $incomingUser->name,
				'email'         => $incomingUser->email,
				'is_google_user'=> true,
			]);
			$user->markEmailAsVerified();
			Auth::login($user);
			return response()->json(['message' => 'User created and logged in successfully', 'user' => $user]);
		} elseif ($user) {
			Auth::login($user);
			return response()->json(['message' => 'User logged in successfully', 'user' => $user]);
		} elseif ($userWithoutGoogleAccount) {
			return response()->json(['message' => 'User can not use google OAuth, they already have plain account'], 403);
		}
	}
}
