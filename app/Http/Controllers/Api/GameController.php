<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Games\GameIndexRequest;
use App\Http\Resources\Games\GameIndexResource;
use App\Models\Game;
use App\Models\GameTag;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GameController extends Controller
{


    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Ajout des middleware nécessaire selon les actions
        $this->middleware('auth:sanctum');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GameIndexRequest $request)
    {
        // Si pas de recherche
        if (empty($request->search)) {
            // Toutes les parties non commencées, non finies, avec le nombre de joueurs en attente, crées dans l'heure
            $allGames = Game::where('has_begun', false)
                ->where('is_finished', false)
                ->orderBy('created_at', 'DESC')
                ->withCount('players')->where(
                    'created_at',
                    '>',
                    Carbon::now()->subDays(20)->toDateTimeString()
                )->get();
            // On vire les parties remplies
            $availableGames = $allGames->filter(function ($game) {
                return $game->players_count < $game->max_players;
            });

            return GameIndexResource::collection($availableGames);
        }
        // Si on a une recherche selon un tag / thème
        else if (!empty($request->search)) {
            // ID du tag correpsondant à la recherche 
            $tag = Tag::where('name', $request->search)->first();
            if (!empty($tag->id)) {
                // Récupération de toutes les parties ayant un rapport avec le thème recherché 
                $gameTagsIds = GameTag::where('tag_id', $tag->id)->orderBy('game_id', 'ASC')->get()->pluck('game_id');
                // Toutes les parties non commencées, non finies, avec le nombre de joueurs en attente, crées dans l'heure
                $allGames = Game::where('has_begun', false)
                    ->where('is_finished', false)
                    ->whereIn('id', $gameTagsIds)
                    ->orderBy('created_at', 'DESC')
                    ->withCount('players')->where(
                        'created_at',
                        '>',
                        Carbon::now()->subDays(20)->toDateTimeString()
                    )->get();
                // On vire les parties remplies
                $availableGames = $allGames->filter(function ($game) {
                    return $game->players_count < $game->max_players;
                });
                return GameIndexResource::collection($availableGames);
            } else {
                return GameIndexResource::collection([]);
            }
        }
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
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(Game $game)
    {
        //
    }
}
