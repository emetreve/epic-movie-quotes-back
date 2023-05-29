<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
	public function redirect()
	{
		$url = Socialite::driver('google')->redirect()->getTargetUrl();
		return response()->json(['url' => $url]);
	}

	public function callback(Request $request)
	{
		// $incomingUser = Socialite::driver('google')->stateless()->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))->user();
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
