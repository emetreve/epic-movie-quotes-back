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

		// $user = User::updateOrCreate([
		// 	'name'          => $incomingUser->name,
		// 	'email'         => $incomingUser->email,
		// 	'is_google_user'=> true,
		// ]);
		// if (!$user->email_verified_at) {
		// 	$user->markEmailAsVerified();
		// }

		// Auth::login($user);
		// session()->regenerate();

		// return response()->json(['user'=>$user]);
		// session()->regenerate();

		$user = User::where('email', $incomingUser->email)->first();

		if (!$user) {
			$user = User::create([
				'name'          => $incomingUser->name,
				'email'         => $incomingUser->email,
				'is_google_user'=> true,
			]);
			$user->markEmailAsVerified();
			Auth::login($user);
			return response()->json(['message' => 'User created successfully', 'user' => $user]);
		} elseif ($user->is_google_user === 1) {
			Auth::login($user);
			session()->regenerate();
			return response()->json(['message' => 'User logged in successfully', 'user' => $user]);
		} else {
			return response()->json(['message' => 'User is not a Google user']);
		}
	}
}
