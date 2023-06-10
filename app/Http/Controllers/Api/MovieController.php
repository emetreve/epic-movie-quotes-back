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
}
