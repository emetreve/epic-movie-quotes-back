<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class GoogleController extends Controller
{
	public function redirect(): RedirectResponse
	{
		return Socialite::driver('google')->redirect();
	}

	public function callback(): RedirectResponse
	{
		$incomingUser = Socialite::driver('google')->user();

		$user = User::updateOrCreate([
			'name' => $incomingUser->name,
			'email'=> $incomingUser->email,
		]);
		if (!$user->email_verified_at) {
			$user->markEmailAsVerified();
		}

		Auth::login($user);
		session()->regenerate();

		$redirectUrl = env('SPA_DOMAIN') . '/dashboard/newsfeed';
		return redirect($redirectUrl);
	}
}
