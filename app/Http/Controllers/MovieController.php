<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = $request->input('page_size', 10);
        $search = $request->input('search');
        $isFeatured = $request->input('is_featured');
        $ordering = $request->input('ordering', 'release_date');

        $orderDirection = Str::startsWith($ordering, '-') ? 'desc' : 'asc';
        $orderField = ltrim($ordering, '-');

        $query = Movie::query();

        if ($search) {
            $query->search($search);
        }

        if (!is_null($isFeatured)) {
            $query->filterByFeatured($isFeatured);
        }

        $query->ordered($orderField, $orderDirection);

        $movies = $query->paginate($pageSize);

        return response()->json([
            'total' => $movies->total(),
            'movies' => $movies->items(),
            'current_page' => $movies->currentPage(),
            'last_page' => $movies->lastPage()
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'release_date' => 'required|date',
            'is_featured' => 'required|boolean',
            'genre' => 'required|string',
        ]);

        $movie = Movie::create($validated);

        return response()->json(['message' => 'Filme criado com sucesso!', 'movie' => $movie], 201);
    }
}
