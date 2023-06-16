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
		$quotes = Quote::factory(2)->create([
			'user_id'  => User::factory(),
			'movie_id' => Movie::factory(),
		]);

		Comment::factory(2)->create([
			'user_id'  => User::factory(),
			'quote_id' => $quotes[0]->id,
		]);

		Comment::factory(3)->create([
			'user_id'  => User::factory(),
			'quote_id' => $quotes[1]->id,
		]);

		Like::factory(5)->create([
			'quote_id' => $quotes[0]->id,
			'user_id'  => User::factory(),
		]);
	}
}
