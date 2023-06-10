<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as KaFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'name' => [
				'en' => $this->faker->sentence,
				'ka' => KaFactory::create('ka_GE')->realText(15),
			],
			'poster' => $this->faker->imageUrl(),
			'year'   => (string)$this->faker->year(),
			'description' => [
				'en' => $this->faker->paragraph(),
				'ka' => KaFactory::create('ka_GE')->realText(40),
			],
			'director' => [
				'en' => $this->faker->name() . ' ' . $this->faker->lastName(),
				'ka' => KaFactory::create('ka_GE')->realText(15),
			],
			'revenue' => (string)$this->faker->numberBetween(200000, 10000000),
			'user_id' => User::factory(),
		];
	}
}
