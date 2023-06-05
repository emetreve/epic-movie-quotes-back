<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
	public function editUserData(UpdateUserRequest $request): JsonResponse
	{
		$data = $request->validated();

		/** @var \App\Models\User $user */
		$user = auth()->user();

		if ($user) {
			if (isset($data['username'])) {
				$user->name = $data['username'];
			}

			if (isset($data['password'])) {
				$user->password = Hash::make($data['password']);
			}

			$user->save();

			return response()->json([
				'success' => true,
				'user'    => $user,
				'message' => 'Request has been fulfilled.',
			], 200);
		}

		return response()->json([
			'success' => false,
			'message' => 'User not found.',
		], 400);
	}
}
