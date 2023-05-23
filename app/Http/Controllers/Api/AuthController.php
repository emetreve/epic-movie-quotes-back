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

		User::create(['name'=>$data['name']]);
	}
}
