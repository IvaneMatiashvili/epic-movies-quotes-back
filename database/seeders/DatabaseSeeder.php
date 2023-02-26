<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Genre;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		$genres = config('genres.genres');

		collect($genres)->each(function ($genre) {
			Genre::create(['genre' => [
				'en' => $genre['en'],
				'ka' => $genre['ka'],
			]]);
		});
	}
}
