<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$genres = [
			['en' => 'Anime', 'ka' => 'ანიმე'],
			['en' => 'Comedy', 'ka' => 'კომედია'],
			['en' => 'Horror', 'ka' => 'საშინელებათა'],
			['en' => 'Action', 'ka' => 'ექშენი'],
			['en' => 'Thriller', 'ka' => 'ტრილერი'],
			['en' => 'Fantasy', 'ka' => 'ფანტასტიკა'],
			['en' => 'Adventure', 'ka' => 'სათავგადასავლო'],
		];

		foreach ($genres as $genre) {
			Genre::firstOrCreate([
				'name' => json_encode($genre),
			]);
		}
	}
}
