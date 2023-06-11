<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as KaFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quote>
 */
class QuoteFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'body' => [
				'en' => trim($this->faker->sentence, '.'),
				'ka' => trim(KaFactory::create('ka_GE')->realText(50), '.'),
			],
			'user_id'  => User::factory(),
			'movie_id' => Movie::factory(),
		];
	}
}
