<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CitySuggestionController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:55'],
        ]);

        if (!Schema::hasTable('bx_cities')) {
            return response()->json(['cities' => []]);
        }

        $query = mb_strtolower(trim($validated['q']));
        $query = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $query);

        $cities = DB::table('bx_cities')
            ->where('country', 5)
            ->whereRaw("LOWER(city) LIKE ? ESCAPE '!'", [$query . '%'])
            ->orderBy('city')
            ->distinct()
            ->limit(10)
            ->pluck('city');

        return response()->json(['cities' => $cities]);
    }
}
