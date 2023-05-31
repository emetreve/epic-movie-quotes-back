<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LogInRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

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

	public function login(LogInRequest $request): JsonResponse
	{
		$attributes = $request->validated();

		$rememberMe = $attributes['remember'];

		$user = User::where('name', $attributes['user'])
		->orWhere('email', $attributes['user'])->first();

		$authWithName = auth()->attempt(['name' => $attributes['user'], 'password' => $attributes['password']], $rememberMe);
		$authWithEmail = auth()->attempt(['email' => $attributes['user'], 'password' => $attributes['password']], $rememberMe);

		// TODO: respond with some custom message if user does not have verified email

		if ($authWithName || $authWithEmail) {
			return response()->json([
				'success' => 200,
			]);
		} else {
			throw ValidationException::withMessages([
				'user' => ['incorrect credensials'],
			]);
		}

		session()->regenerate();
	}

	public function resendEmailLink(Request $request): JsonResponse
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

	public function getUser(): JsonResponse
	{
		return response()->json(['user' => auth()->user()]);
	}

	public function checkIfLoggedIn(): JsonResponse
	{
		return response()->json([
			'success' => 200,
		]);
	}

	public function logout()
	{
		auth()->logout();

		request()->session()->invalidate();

		request()->session()->regenerateToken();

		return response(['message' => 'User was logged out']);
	}
}
