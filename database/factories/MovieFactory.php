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
				'en' => trim($this->faker->sentence, '.'),
				'ka' => trim(KaFactory::create('ka_GE')->realText(30), '.'),
			],
			'year'        => (string)$this->faker->year(),
			'description' => [
				'en' => trim($this->faker->paragraph(), '.'),
				'ka' => trim(KaFactory::create('ka_GE')->realText(70), '.'),
			],
			'director' => [
				'en' => $this->faker->name() . ' ' . $this->faker->lastName(),
				'ka' => trim(KaFactory::create('ka_GE')->realText(18), '.'),
			],
			'revenue' => (string)$this->faker->numberBetween(200000, 10000000),
			'user_id' => User::factory(),
		];
	}
}
