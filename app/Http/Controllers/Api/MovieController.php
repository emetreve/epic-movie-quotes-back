<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    public function index(Request $request)
	{
		$movies = Movie::with('user')->orderBy('created_at', 'desc')->get();
		return response()->json($movies);
	}

	public function userMovies(Request $request)
	{
		$user = auth()->user();

		$movies = Movie::where('user_id', $user->id)->withCount('quotes')
			->orderBy('created_at', 'desc')
			->get();

		$movies = $movies->makeHidden(['description', 'director', 'revenue']);

		return response()->json($movies);
	}
}
