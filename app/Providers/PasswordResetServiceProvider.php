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
			// $url = url(route('password.reset', ['token' => $token, 'email' => $user->getEmailForPasswordReset()], false));
			$spaDomain = config('app.spa_domain');
			$url = $spaDomain . '/?token=' . $token . '&email=' . $user->getEmailForPasswordReset();

			return (new MailMessage)
				->subject(__('password-reset.email_subject'))
				->line(__('password-reset.email_line'))
				->action(__('password-reset.email_action'), $url);
		});
	}
}
