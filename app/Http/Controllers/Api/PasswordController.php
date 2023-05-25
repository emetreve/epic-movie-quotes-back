<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\ForgotPasswordRequest;

class PasswordController extends Controller
{
	public function requestChange(ForgotPasswordRequest $request)
	{
		$request->validated();

		$status = Password::sendResetLink($request->only('email'));

		return $status === Password::RESET_LINK_SENT
			? response()->json(['message' => 'Reset link sent successfully'], 200)
			: response()->json(['error' => 'Unable to send reset link'], 422);
	}
}
