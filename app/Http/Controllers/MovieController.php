<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
	public function getGenres(): JsonResponse
	{
		return response()->json(config('genres.genres'), 200);
	}

	public function getMovies(): JsonResponse
	{
		$movies = auth()->user()->movies;
		return response()->json($movies, 200);
	}

	public function getMovie(): JsonResponse
	{
		$movie = Movie::where('id', request()->query('movie_id'))->first();
		return response()->json([$movie, $movie->genres], 200);
	}

	public function storeMovie(Request $request): JsonResponse
	{
		$user = auth()->user();
		$movie = Movie::create([
			'title' => [
				'en' => $request['name_en'],
				'ka' => $request['name_ka'],
			],
			'director' => [
				'en' => $request['director_en'],
				'ka' => $request['director_ka'],
			],
			'description' => [
				'en' => $request['description_en'],
				'ka' => $request['description_ka'],
			],
			'release_date' => $request['release_date'],
			'budget'       => $request['budget'],
			'thumbnail'    => Storage::url($request->file('thumbnail')->store('movie_thumbnails')),
			'user_id'      => $user->id,
		]);
		$genresIds = json_decode($request['genres_ids'], true);

		$movie->genres()->attach($genresIds);
		return response()->json($movie, 201);
	}

	public function editMovie(Request $request): JsonResponse
	{
		$user = auth()->user();
		$movie = Movie::where('id', $request['movie_id'])->first();

		$movie->update([
			'title' => [
				'en' => $request['name_en'],
				'ka' => $request['name_ka'],
			],
			'director' => [
				'en' => $request['director_en'],
				'ka' => $request['director_ka'],
			],
			'description' => [
				'en' => $request['description_en'],
				'ka' => $request['description_ka'],
			],
			'release_date' => $request['release_date'],
			'budget'       => $request['budget'],
			'user_id'      => $user->id,
		]);

		if (isset($request['thumbnail']))
		{
			$movie->update([
				'thumbnail'    => Storage::url($request->file('thumbnail')->store('movie_thumbnails')),
			]);
		}

		$genresIds = json_decode($request['genres_ids'], true);

		$movie->genres()->attach($genresIds);
		return response()->json($movie, 201);
	}

	public function searchMovies(Request $request): JsonResponse
	{
		return response()->json(Movie::filter(['search' => $request['search']])->get(), 200);
	}

	public function deleteMovie(Request $request): JsonResponse
	{
		$movie = Movie::where('id', $request['movie_id'])->first();
		$movie->delete();

		return response()->json(['success' => 'movie deleted successfully'], 200);
	}
}
