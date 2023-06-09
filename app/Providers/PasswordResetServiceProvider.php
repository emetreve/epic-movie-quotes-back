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

			$locale = request()->query('locale');

			app()->setLocale($locale);

			$url = $spaDomain . '/' . $locale . '/?token=' . $token . '&email=' . $user->getEmailForPasswordReset();
			$userName = $user->name;

			return (new MailMessage)
				->subject(__('reset-password.reset_password'))
				->line(__('reset-password.reset_password'))
				->view('emails.reset-password', ['url' => $url, 'name'=>$userName]);
		});
	}
}
