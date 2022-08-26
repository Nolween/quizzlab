<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Games\GameIndexRequest;
use App\Http\Requests\Games\GameJoinRequest;
use App\Http\Requests\Games\GameStoreRequest;
use App\Http\Resources\Games\GameIndexResource;
use App\Http\Resources\Games\GameJoinResource;
use App\Http\Resources\Games\GameStoreResource;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\GameQuestion;
use App\Models\GameTag;
use App\Models\Question;
use App\Models\Tag;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $userId = Auth::id();
        // Si pas de recherche
        if (empty($request->search)) {
            // Toutes les parties non commencées, non finies, avec le nombre de joueurs en attente, crées dans l'heure
            // Soit des parties non commencées et non finies peu importe les joueurs dedans
            $allGames = Game::where(function ($q) {
                $q->where('has_begun', false)
                    ->where('is_finished', false);
            })
                // Soit des parties non finies ou l'utilisateur est présent
                ->orWhere(function ($q)  use ($userId) {
                    $q->where('has_begun', true)
                        ->where('is_finished', false)
                        ->whereHas('players', function ($query) use ($userId) {
                            $query->where('user_id', $userId);
                        });
                })
                ->orderBy('has_begun', 'DESC')
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

            return GameIndexResource::collection($allGames);
        }
        // Si on a une recherche selon un tag / thème
        else if (!empty($request->search)) {
            // ID du tag correpsondant à la recherche 
            $tag = Tag::where('name', $request->search)->first();
            if (!empty($tag->id)) {
                // Récupération de toutes les parties ayant un rapport avec le thème recherché 
                $gameTagsIds = GameTag::where('tag_id', $tag->id)->orderBy('game_id', 'ASC')->get()->pluck('game_id');
                // Toutes les parties non commencées, non finies, avec le nombre de joueurs en attente, crées dans l'heure
                $allGames = Game::where(function ($q) use ($gameTagsIds) {
                    $q->where('has_begun', false)
                        ->where('is_finished', false)
                        ->whereIn('id', $gameTagsIds);
                })
                    // Soit des parties non finies ou l'utilisateur est présent
                    ->orWhere(function ($q)  use ($userId, $gameTagsIds) {
                        $q->where('has_begun', true)
                            ->where('is_finished', false)
                            ->whereIn('id', $gameTagsIds)
                            ->whereHas('players', function ($query) use ($userId) {
                                $query->where('user_id', $userId);
                            });
                    })
                    ->orderBy('has_begun', 'DESC')
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
     * Informations de la partie dans la salle d'attente
     *
     * @param  \App\Http\Requests\Games\GameJoinRequest $request
     * @param  \App\Models\Game $game
     * @return \App\Http\Resources\Games\GameJoinResource
     */
    public function join(GameJoinRequest $request, Game $game)
    {
        $user = Auth::user();
        
        // Retour dans le front des informations
        return new GameJoinResource($game);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GameStoreRequest $request)
    {
        $userId = Auth::user()->id;

        // L'utilisateur est-il encore dans une partie en cours?
        $userInGames = Game::with(
            ['players' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }]
        )
            ->where('is_finished', false)
            ->where('created_at', '>', Carbon::now()->subMinutes(10))
            ->where('user_id', $userId)->get();

        // Si le joueur est encore dans des parties en cours, refus
        if (count($userInGames) > 0) {
            return response()->json(['success' => false, 'message' => "Veuillez attendre 10 minutes"], 500);
        }

        // Génération d'un code de partie si plus d'un joueur
        $gameCode = $request->maxPlayers > 1 ? fake()->regexify('[A-Z]{4}-[0-4]{4}') : null;
        DB::beginTransaction();
        try {
            // Création de la partie
            $newGame = Game::create([
                'user_id' => $userId,
                'game_rule_id' => 1,
                'max_players' => $request->maxPlayers,
                'response_time' => $request->responseTime,
                'question_count' => $request->questionCount,
                'has_begun' => false,
                'is_finished' => false,
                'game_code' => $gameCode,
                'questions_have_all_tags' => !empty($request->allTags) ? $request->allTags : false,
            ]);
            // Assignation du joueur dans la file d'attente
            GamePlayer::create([
                'game_id' => $newGame->id,
                'user_id' => $userId,
            ]);
            // Assignation des thèmes pour la partie
            if (!empty($request->selectedThemes)) {
                foreach ($request->selectedThemes as $selectedTheme) {
                    // Le thème existe t-il?
                    $tag = Tag::where('name', $selectedTheme)->get();
                    GameTag::create([
                        'game_id' => $newGame->id,
                        'tag_id' => $tag->id
                    ]);
                }
            }
            // Récupération des tags dans un tableau d'ids
            $tagIds = GameTag::where('game_id', $newGame->id)->get()->pluck('tag_id');
            $gameQuestionsIds = [];
            //? Si aucun thème n'a été sélectionné
            if (empty($request->selectedThemes)) {
                // Selon le nombre de question dans la partie
                for ($i = 1; $i <= $request->question_count; $i++) {
                    // On cherche une Id de question pas encore dans la partie
                    $questionToAddId = Question::where('is_integrated', true)->whereNotIn('id', $gameQuestionsIds)->inRandomOrder()->get()->id;
                    // Attribution de la question à la partie
                    GameQuestion::create([
                        'game_id' => $newGame->id,
                        'question_id' => $questionToAddId,
                        'order' => $i,
                    ]);
                    // Ajout de la question dans les ids de questions utilisées
                    $gameQuestionsIds[] = $questionToAddId;
                }
            }
            //? Si des thèmes sont associés
            else {
                // Selon le nombre de question dans la partie
                for ($i = 1; $i <= $request->question_count; $i++) {
                    // On cherche une Id de question pas encore dans la partie
                    //? Si il faut seuelement que la question comporte un des thèmes associés
                    if ($request->allTags == true) {
                        $questionToAddId = Question::whereHas('tags', function (Builder $query) use ($tagIds) {
                            $query->whereIn('tag_id', $tagIds);
                        })->where('is_integrated', true)->whereNotIn('id', $gameQuestionsIds)->inRandomOrder()->first()->id;
                    }
                    //? Si chaque question doit comporter tous les thèmes associés
                    else {
                        // Quels sont tous les thèmes associés de la partie?
                        $tagIds = GameTag::where('game_id', $newGame->id)->get()->pluck('tag_id');
                        $questionToAddId = Question::whereHas('tags', function (Builder $query) use ($tagIds) {
                            $query->whereIn('tag_id', $tagIds);
                        }, '>=', count($tagIds))->where('is_integrated', true)->whereNotIn('id', $gameQuestionsIds)->inRandomOrder()->first()->id;
                    }
                    // Attribution de la question à la partie
                    GameQuestion::create([
                        'game_id' => $newGame->id,
                        'question_id' => $questionToAddId,
                        'order' => $i,
                    ]);
                    // Ajout de la question dans les ids de questions utilisées
                    $gameQuestionsIds[] = $questionToAddId;
                }
            }
            // Validation de la transaction
            DB::commit();

            return new GameStoreResource($newGame);
        }
        // Si erreur
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
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
