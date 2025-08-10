<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;


class ArticleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Article::with([
            'creator:id,name',
            'rubric:id,name,slug',
            'media'
        ]);

        // Filtre par slug
        if ($request->has('rubric')) {
            $slug = $request->get('rubric');
            $query->whereHas('rubric', function ($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }

        // Filtre par rubric_id
        if ($request->has('rubric_id')) {
            $query->where('rubric_id', $request->get('rubric_id'));
        }

        // Exclusion par slug
        if ($request->has('exclude_rubric')) {
            $slug = $request->get('exclude_rubric');
            $query->whereHas('rubric', function ($q) use ($slug) {
                $q->where('slug', '!=', $slug);
            });
        }

        // Exclusion par rubric_id
        if ($request->has('exclude_rubric_id')) {
            $query->where('rubric_id', '!=', $request->get('exclude_rubric_id'));
        }

        // Trier du plus récent au plus ancien
        $query->orderBy('created_at', 'desc');

        $articles = $query->paginate($request->get('per_page', 10));

        return response()->json($articles);
    }
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('query', '');
        $articles = Article::with(['rubric', 'media'])
            ->where('title', 'like', '%' . $query . '%')
            ->orWhere('content', 'like', '%' . $query . '%')
            ->paginate($request->get('per_page', 10));

        return response()->json($articles);
    }


    public function show(string $id): JsonResponse
    {
        $article = Article::with('rubric')->findOrFail($id);
        return response()->json($article);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'rubric_id' => 'required|exists:rubrics,id',
            'media' => 'array', // pour plusieurs fichiers
            'media.*' => 'file|mimes:jpeg,png,mp4,avif,jpg,gif,svg,avi,mov,wmv|max:20480',
        ]);

        // Création de l'article avec les données validées hors fichiers
        $article = Article::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'rubric_id' => $validated['rubric_id'],
            'media' => [],
            'created_by' => auth()->id(),
        ]);

        // Si des fichiers images sont envoyés, on les traite
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                // Stockage dans storage/app/public/articles
                $path = $file->store('public/articles');

                // Optionnel : récupérer l'URL publique (nécessite lien symbolique avec 'php artisan storage:link')
                $url = Storage::url($path);
                $mimeType = $file->getClientMimeType();

                if (str_starts_with($mimeType, 'image/')) {
                    $type = 'image';
                } elseif (str_starts_with($mimeType, 'video/')) {
                    $type = 'video';
                } elseif (str_starts_with($mimeType, 'audio/')) {
                    $type = 'audio';
                } else {
                    $type = 'unknown';
                }
                // Création d'un média lié à l'article
                $article->media()->create([
                    'title' => $file->getClientOriginalName(),               // Par exemple, nom du fichier comme titre
                    'description' => '',                                     // Description vide ou à remplir selon besoin
                    'type' => $type,                    // Type MIME (ex: image/jpeg, video/mp4)
                    'url' => $url,                                           // URL publique du fichier
                    'filename' => $file->getClientOriginalName(),            // Nom original du fichier
                    'size' => $file->getSize(),                              // Taille en octets
                ]);
            }
        }

        $article->load('rubric', 'media');

        return response()->json($article, 201);
    }


    public function update(Request $request, string $id): JsonResponse
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'rubric_id' => 'sometimes|required|exists:rubrics,id',
            'media' => 'sometimes|array',
            'media.*' => 'exists:media,id',
        ]);

        $article->update($validated);
        $article->load('rubric');

        return response()->json($article);
    }

    public function destroy(string $id): JsonResponse
    {
        $article = Article::findOrFail($id);
        $article->delete();
        return response()->json(null, 204);
    }

    public function getByRubric(string $rubricId): JsonResponse
    {
        $articles = Article::where('rubric_id', $rubricId)
            ->with('rubric')
            ->get();

        return response()->json($articles);
    }
}
