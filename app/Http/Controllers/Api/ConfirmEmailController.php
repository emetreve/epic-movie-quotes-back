<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;

class ConfirmEmailController extends Controller
{
	public function verifyEmail(EmailVerificationRequest $request)
	{
		$request->fulfill();

		Auth::logout();

		return response(['message' => 'email verified']);
	}
}
