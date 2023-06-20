<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Comment;
use App\Models\Like;
use App\Models\User;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$user = User::factory()->create();

		$movie = Movie::factory()->create();

		$quotes = Quote::factory(5)->create([
			'user_id'  => $user->id,
			'movie_id' => $movie->id,
		]);

		Comment::factory(1)->create([
			'user_id'  => User::factory(),
			'quote_id' => $quotes[0]->id,
		]);

		Like::factory(1)->create([
			'quote_id' => $quotes[0]->id,
			'user_id'  => $user->id,
		]);
	}
}
