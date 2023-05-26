<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;

class PasswordController extends Controller
{
	public function requestChange(ForgotPasswordRequest $request): JsonResponse
	{
		$request->validated();

		$status = Password::sendResetLink($request->only('email'));

		return $status === Password::RESET_LINK_SENT
			? response()->json(['message' => 'Reset link sent successfully'], 200)
			: response()->json(['errors' => ['email' => [__($status)]]], 422);
	}

	public function reset(ResetPasswordRequest $request): JsonResponse
	{
		$request->validated();

		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function (User $user, string $password) {
				$user->forceFill([
					'password' => Hash::make($password),
				])->setRememberToken(Str::random(60));

				if (!$user->hasVerifiedEmail()) {
					$user->markEmailAsVerified();
				}
				$user->save();
			}
		);

		return $status === Password::PASSWORD_RESET
			? response()->json(['message' => __($status)], 200)
			: response()->json(['errors' => ['password' => [__($status)]]], 422);
	}
}
