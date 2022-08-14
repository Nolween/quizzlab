<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\TagSearchRequest;
use App\Http\Resources\Tags\TagIndexResource;
use App\Http\Resources\Tags\TagSearchResource;
use App\Models\Tag;
use App\Services\ElasticService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Affiche la totalité des tags
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TagIndexResource::collection(Tag::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        //
    }

    public function search(TagSearchRequest $request, ElasticService $elasticService)
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
        } catch (Exception $e) {
            return response()->json(['message' => "Erreur dans la requete"], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        //
    }
}
