<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ProfileController extends Controller
{
	public function update(UpdateUserRequest $request): JsonResponse
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

			if (isset($data['avatar'])) {
				$user->avatar = '/storage/' . $request->file('avatar')->store('avatars');
			}

			$user->save();

			$oldEmail = $user->email;
			if (isset($data['email']) && $oldEmail !== $data['email']) {
				$locale = $data['locale'];
				app()->setLocale($locale);
				$newEmail = $data['email'];
				Mail::send('emails.verify-email', ['name' => $user->name, 'url' => config('app.spa_domain') . '/' . $locale . '/dashboard/profile?changeEmail=' . $newEmail], function ($message) use ($newEmail) {
					$message->to($newEmail)->subject(__('verify-email.verify_email'));
				});
			}

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

	public function changeEmail(ChangeEmailRequest $request)
	{
		$data = $request->validated();
		$email = $data['email'];

		/** @var \App\Models\User $user */
		$user = auth()->user();

		$user->email = $email;
		$user->updated_at = now();
		$user->markEmailAsVerified();
		$user->save();

		return response()->json(['success' => true], 200);
	}
}
