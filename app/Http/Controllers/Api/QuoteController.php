<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quote;

class QuoteController extends Controller
{
	public function index(Request $request)
	{
		$quotes = Quote::with('movie', 'user', 'comments.user')->orderBy('created_at', 'desc')->get();
		return response()->json($quotes);
	}
}
