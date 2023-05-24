<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The model to policy mappings for the application.
	 *
	 * @var array<class-string, class-string>
	 */
	protected $policies = [
	];

	/**
	 * Register any authentication / authorization services.
	 */
	public function boot(): void
	{
		VerifyEmail::toMailUsing(function ($notifiable, $url) {
			$spaDomain = 'http://localhost:3000/';

			$path = parse_url($url, PHP_URL_PATH);
			$query = parse_url($url, PHP_URL_QUERY);

			$pathSegments = explode('/', $path);
			$id = $pathSegments[4];
			$token = $pathSegments[5];
			$transformedUrl = rtrim($spaDomain, '/') . '/?' . http_build_query(['id' => $id, 'token' => $token]);

			if ($query) {
				$transformedUrl .= '&' . $query;
			}

			return (new MailMessage)
				->subject('email-verification.email_subject')
				->line('email-verification.email_line')
				->action('email-verification.email_action', $transformedUrl);
		});
	}
}
