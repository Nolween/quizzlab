<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\TagQuestionsCountRequest;
use App\Http\Requests\Tags\TagSearchRequest;
use App\Http\Resources\Tags\TagIndexResource;
use App\Http\Resources\Tags\TagSearchResource;
use App\Models\Question;
use App\Models\Tag;
use App\Services\ElasticService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TagController extends Controller
{
    /**
     * Affiche la totalité des tags
     */
    public function index(): AnonymousResourceCollection
    {
        return TagIndexResource::collection(Tag::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        //
        return response();
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag): Response
    {
        //
        return response($tag);
    }

    public function search(TagSearchRequest $request, ElasticService $elasticService): JsonResponse|TagSearchResource
    {
        try {
            // Connexion au serveur Elastic pour récupérer les 5 résultats les plus probables
            $response = $elasticService->getWildcardFromIndex('tags', 5, 'tag', $request->tag);
            // Si on obtient bien des résultats
            if ($response->ok()) {
                return new TagSearchResource($response->json());
            } else {
                return response()->json(['message' => "Erreur dans l'accès aux thèmes'"], 404);
            }
        } catch (Exception) {
            return response()->json(['message' => 'Erreur dans la requête'], 500);
        }
    }

    public function questionsCount(TagQuestionsCountRequest $request): JsonResponse
    {
        try {
            // Si pas de thèmes sélectionnés et pas de liaison de thèmes
            if (empty($request->tags) && $request->allTags == 0) {
                // Combien de questions intégrées au quizz
                $totalQuestions = Question::where('is_moderated', true)->where('is_integrated', true)->get()->count();

                return response()->json(['possibleQuestions' => $totalQuestions]);
            }
            // Si un/des thèmes sélectionnés
            elseif (! empty($request->tags)) {
                // Récupération des ids de tags
                $tagsIdsArray = Tag::whereIn('name', $request->tags)->get()->pluck('id');
                // Si pas de liaison de thèmes
                if ($request->allTags == 0) {
                    $totalQuestions = Question::whereHas('tags', function (Builder $query) use ($tagsIdsArray) {
                        $query->whereIn('tag_id', $tagsIdsArray);
                    })->where('is_integrated', true)->get()->count();
                }
                // Si liaison de thèmes
                else {
                    $totalQuestions = Question::whereHas('tags', function (Builder $query) use ($tagsIdsArray) {
                        $query->whereIn('tag_id', $tagsIdsArray);
                    }, '>=', count($tagsIdsArray))->where('is_integrated', true)->get()->count();
                }

                return response()->json(['possibleQuestions' => $totalQuestions]);
            } else {
                return response()->json(['message' => "Erreur dans l'accès aux thèmes'"], 404);
            }
        } catch (Exception) {
            return response()->json(['message' => 'Erreur dans la requête'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag): Response
    {
        //
        return response($tag);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag): Response
    {
        //
        return response($tag);
    }
}
