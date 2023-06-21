<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Genre;

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

	public function getGenres()
	{
		$genres = Genre::all();

		$genres = $genres->map(function ($genre) {
			$genre->name = json_decode($genre->name);
			return $genre;
		});
		$genres = $genres->makeHidden(['created_at', 'updated_at']);

		return response()->json($genres);
	}

	public function store(StoreMovieRequest $request)
	{
		$movie = new Movie();

		$movie->poster = '/storage/' . $request->file('image')->store('movies');

		$movie->name = [
			'en' => $request->input('nameEn'),
			'ka' => $request->input('nameGe'),
		];

		$movie->user_id = auth()->user()->id;

		$movie->director = [
			'en' => $request->input('directorEn'),
			'ka' => $request->input('directorGe'),
		];

		$movie->description = [
			'en' => $request->input('descriptionEn'),
			'ka' => $request->input('descriptionGe'),
		];

		$movie->year = $request->input('year');
        $movie->revenue = $request->input('revenue');

		$movie->save();

		$genres = json_decode($request->input('genres'));
		foreach ($genres as $genre) {
			$genreId = $genre->id;
			DB::table('genre_movie')->insert([
				'genre_id' => $genreId,
				'movie_id' => $movie->id,
			]);
		}

		return response()->json(['message' => 'movie created'], 201);
	}
}
