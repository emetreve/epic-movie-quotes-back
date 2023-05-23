<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LogInRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
	public function signup(SignUpRequest $request)
	{
		$data = $request->validated();

		$user = User::create([
			'name'    => $data['name'],
			'email'   => $data['email'],
			'password'=> bcrypt($data['password']),
		]);

		$token = $user->createToken('main')->plainTextToken;

		return response(compact('user', 'token'));
	}

	public function login(LogInRequest $request)
	{
		$credentials = $request->validated();

		if (!Auth::attempt($credentials)) {
			return response([
				'message'=> 'Provided credentials are incorrect.',
			]);
		}

		/** @var User $user */
		$user = Auth::user();
		$token = $user->createToken('main')->plainTextToken;

		return response(compact('user', 'token'));
	}

	public function logout(Request $request)
	{
		$user = $request->user();
		$user->currentAccessToken()->delete();

		return response('', 204);
	}
}
