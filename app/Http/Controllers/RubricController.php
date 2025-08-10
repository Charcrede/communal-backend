<?php

namespace App\Http\Controllers;

use App\Models\Rubric;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RubricController extends Controller
{
    public function index(): JsonResponse
    {
        $rubrics = Rubric::with('articles')->get();
        return response()->json($rubrics);
    }

    public function show(string $id): JsonResponse
    {
        $rubric = Rubric::with('articles')->findOrFail($id);
        return response()->json($rubric);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $rubric = Rubric::create($validated);
        return response()->json($rubric, 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $rubric = Rubric::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
        ]);

        $rubric->update($validated);
        return response()->json($rubric);
    }

    public function destroy(string $id): JsonResponse
    {
        $rubric = Rubric::findOrFail($id);
        $rubric->delete();
        return response()->json(null, 204);
    }
}