<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

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
			$spaDomain = config('app.spa_domain');

			$path = parse_url($url, PHP_URL_PATH);
			$query = parse_url($url, PHP_URL_QUERY);

			$locale = request()->query('locale', 'en');
			app()->setLocale($locale);

			$pathSegments = explode('/', $path);
			$id = $pathSegments[4];
			$token = $pathSegments[5];
			$transformedUrl = rtrim($spaDomain, '/') . '/' . $locale . '/?' . http_build_query(['id' => $id, 'token' => $token]);

			if ($query) {
				$transformedUrl .= '&' . $query;
			}

			$userName = $user = User::find($id)->name;

			return (new MailMessage)
				->subject(__('verify-email.verify_account'))
				->line(__('verify-email.verify_account'))
				->view('emails.verify-email', ['url' => $transformedUrl, 'name'=>$userName]);
		});
	}
}
