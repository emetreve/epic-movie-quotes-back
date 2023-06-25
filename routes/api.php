<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConfirmEmailController;
use App\Http\Controllers\Api\GoogleController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\NotificationController;

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
	Route::get('/logout', 'logout')->middleware('auth')->name('logout');
	Route::post('/signup', 'signup')->name('signup');
	Route::get('/resend-email-verification-link', 'resendEmailLink')->name('resendEmailLink');
});

Route::controller(GoogleController::class)->group(function () {
	Route::get('/auth/redirect', 'redirect');
	Route::get('/auth/callback', 'callback');
});

Route::get('/email/verify/{id}/{hash}', [ConfirmEmailController::class, 'verifyEmail'])->name('verification.verify');

Route::post('/forgot-password', [PasswordController::class, 'requestChange'])->name('password.email');

Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.update');

Route::middleware(['verified', 'auth:sanctum', 'auth'])->group(function () {
	Route::get('/check', [AuthController::class, 'checkIfLoggedIn'])->name('check');
	Route::get('/user', [AuthController::class, 'getUser'])->name('user');
	Route::post('/edit-user-data', [ProfileController::class, 'update'])->name('update.user');

	Route::get('/like', [QuoteController::class, 'like'])->name('like');
	Route::get('/genres', [GenreController::class, 'index'])->name('genres');

	Route::post('/create-comment', [CommentController::class, 'store'])->name('create.comment');
	Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
	Route::get('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark.all');
	Route::get('/mark-one-read', [NotificationController::class, 'markOneRead'])->name('notifications.mark.one');

	Route::prefix('quotes')->group(function () {
		Route::get('/', [QuoteController::class, 'index'])->name('quotes');
		Route::post('/', [QuoteController::class, 'store'])->name('quotes.create');
		Route::delete('/{quote}', [QuoteController::class, 'destroy'])->name('quotes.destroy');
	});

	Route::prefix('movies')->group(function () {
		Route::get('/', [MovieController::class, 'index'])->name('movies.index');
		Route::get('/user', [MovieController::class, 'userMovies'])->name('movies.user');
		Route::post('/', [MovieController::class, 'store'])->name('movies.store');
		Route::get('/{movie}', [MovieController::class, 'get'])->name('movies.get');
		Route::delete('/{movie}', [MovieController::class, 'destroy'])->name('movies.destroy');
		Route::post('update/{movie}', [MovieController::class, 'update'])->name('movies.update');
	});
});
