<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(): JsonResponse
    {
        $media = Media::all();
        return response()->json($media);
    }

    public function show(string $id): JsonResponse
    {
        $media = Media::findOrFail($id);
        return response()->json($media);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:image,video,audio',
            'file' => 'required|file|max:51200', // 50MB max
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('media', $filename, 'public');

        $media = Media::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'url' => Storage::url($path),
            'filename' => $filename,
            'size' => $file->getSize(),
        ]);

        return response()->json($media, 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $media = Media::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:image,video,audio',
        ]);

        $media->update($validated);
        return response()->json($media);
    }

    public function destroy(string $id): JsonResponse
    {
        $media = Media::findOrFail($id);
        
        // Supprimer le fichier du stockage
        $path = str_replace('/storage/', '', $media->url);
        Storage::disk('public')->delete($path);
        
        $media->delete();
        return response()->json(null, 204);
    }
}