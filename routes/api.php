<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConfirmEmailController;
use App\Http\Controllers\Api\PasswordController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

Route::controller(AuthController::class)->group(function () {
	Route::post('/login', 'login')->name('login');
	Route::post('/signup', 'signup')->name('signup');
	Route::get('/resend-email-verification-link', 'resendEmailLink')->name('resendEmailLink');
});

// Route::controller(GoogleController::class)->group(function () {
Route::get('/auth/redirect', function () {
	return Socialite::driver('google')->redirect();
});
Route::get('/auth/callback', function () {
	$incomingUser = Socialite::driver('google')->user();

	$user = User::updateOrCreate([
		'name' => $incomingUser->name,
		'email'=> $incomingUser->email,
	]);
	if (!$user->email_verified_at) {
		$user->markEmailAsVerified();
	}

	Auth::login($user);
	session()->regenerate();

	$redirectUrl = env('SPA_DOMAIN') . '/dashboard/newsfeed';
	return redirect($redirectUrl);
});
// });

Route::get('/email/verify/{id}/{hash}', [ConfirmEmailController::class, 'verifyEmail'])->name('verification.verify');

Route::post('/forgot-password', [PasswordController::class, 'requestChange'])->name('password.email');

Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.update');

Route::middleware(['verified', 'auth:sanctum'])->group(function () {
	Route::get('/check', [AuthController::class, 'checkIfLoggedIn'])->name('check');
	Route::get('/user', [AuthController::class, 'getUser'])->name('user');
});
