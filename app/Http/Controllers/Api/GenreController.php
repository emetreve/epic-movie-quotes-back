<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;

class GenreController extends Controller
{
	public function index()
	{
		$genres = Genre::all();

		$genres = $genres->makeHidden(['created_at', 'updated_at']);

		return response()->json($genres);
	}
}
