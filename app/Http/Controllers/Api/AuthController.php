<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpRequest;
use App\Models\User;

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

		/** @var User $user */
		$token = $user->createToken('main')->plainTextToken;

		return response(compact('user', 'token'));
	}
}
