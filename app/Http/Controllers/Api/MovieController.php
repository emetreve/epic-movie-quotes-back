<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
	public function index(Request $request)
	{
		$movies = Movie::with('user')->orderBy('created_at', 'desc')->get();
		return response()->json($movies);
	}

	public function get($id)
	{
		$user = auth()->user();

		$movie = Movie::where('id', $id)->where('user_id', $user->id)->with(['genres', 'quotes' => function ($query) {
			$query->with('likes')->withCount(['likes', 'comments'])->orderBy('updated_at', 'desc');
		}])->withCount('quotes')->first();

		if ($movie) {
			return response()->json($movie, 200);
		} else {
			return response()->json(['error' => 'User cannot access this movie or movie with such id does not exist.'], 401);
		}
	}

	public function userMovies(Request $request)
	{
		$user = auth()->user();

		$query = Movie::where('user_id', $user->id)->withCount('quotes')
			->orderBy('created_at', 'desc');

		if ($request->has('search')) {
			$query->searchByName($request->query('search'), $request->query('locale'));
		}

		$movies = $query->get();

		$movies = $movies->makeHidden(['description', 'director', 'revenue']);

		return response()->json($movies);
	}

	public function store(StoreMovieRequest $request)
	{
		$movie = new Movie();

		$movie->poster = '/storage/' . $request->file('image')->store('movies');

		$movie->user_id = auth()->user()->id;

		$movie->year = $request->input('year');
		$movie->revenue = $request->input('revenue');

		$movie->name = json_decode($request->input('name'), true);

		$movie->director = json_decode($request->input('director'), true);

		$movie->description = json_decode($request->input('description'), true);

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

	public function destroy($id)
	{
		$movie = Movie::find($id);

		if ($movie) {
			$movie->quotes()->delete();
			$movie->genres()->detach();
			$movie->delete();
			return response()->json(['message' => 'Movie deleted successfully'], 200);
		} else {
			return response()->json(['error' => 'Movie not found'], 404);
		}
	}

	public function update(UpdateMovieRequest $request, Movie $movie)
	{
		if ($request->file('image')) {
			$movie->poster = '/storage/' . $request->file('image')->store('movies');
		}

		$movie->user_id = auth()->user()->id;
		$movie->year = $request->input('year');
		$movie->revenue = $request->input('revenue');

		$movie->update([
			'name' => json_decode($request->input('name'), true),
		]);

		$movie->update([
			'director' => json_decode($request->input('director'), true),
		]);

		$movie->update([
			'description' => json_decode($request->input('description'), true),
		]);

		$movie->save();

		$genres = $request->input('genres');

		if ($genres) {
			$genreIds = collect(json_decode($genres))->pluck('id')->toArray();
			$movie->genres()->sync($genreIds);
		}

		return response()->json($movie);
	}
}
