<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
	public function requestChange(Request $request)
	{
		$request->validate([
			'email' => 'required|email',
		]);

		$status = Password::sendResetLink($request->only('email'));

		return $status === Password::RESET_LINK_SENT
			? response()->json(['message' => 'Reset link sent successfully'], 200)
			: response()->json(['error' => 'Unable to send reset link'], 422);
	}
}
