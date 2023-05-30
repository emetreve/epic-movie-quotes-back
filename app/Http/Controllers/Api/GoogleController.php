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

		$user = User::updateOrCreate([
			'name' => $incomingUser->name,
			'email'=> $incomingUser->email,
		]);
		if (!$user->email_verified_at) {
			$user->markEmailAsVerified();
		}

		Auth::login($user);
		session()->regenerate();

		return response()->json(['user'=>$user]);
		session()->regenerate();
	}
}
