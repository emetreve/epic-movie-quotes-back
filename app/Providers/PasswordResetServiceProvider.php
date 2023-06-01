<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 */
	public function register(): void
	{
	}

	/**
	 * Bootstrap services.
	 */
	public function boot(): void
	{
		ResetPassword::toMailUsing(function (User $user, $token) {
			$spaDomain = config('app.spa_domain');
			$url = $spaDomain . '/?token=' . $token . '&email=' . $user->getEmailForPasswordReset();
			$userName = $user->name;

			return (new MailMessage)
				->subject('Reset Password')
				->line('Reset Password')
				->view('emails.reset-password', ['url' => $url, 'name'=>$userName]);
		});
	}
}
