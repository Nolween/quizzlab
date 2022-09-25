<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameResults\GameResultStoreRequest;
use App\Models\Game;
use App\Models\GameResult;
use App\Rules\GameQuestions\BelongsToGameRule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

class GameResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        //
        return response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GameResultStoreRequest $request
     * @return JsonResponse
     */
    public function store(GameResultStoreRequest $request): JsonResponse
    {
        // Validation supplémentaire
        $request->validate([
            'game_id' => [new BelongsToGameRule($request->game_question_id)]
        ]);
        // Début de la transaction
        DB::beginTransaction();
        try {
            $endGame = false;
            // Déjà des résultats envoyés pour cette question ?
            $noResults = GameResult::where('game_question_id', $request->game_question_id)->doesntExist();
            if ($noResults) {
                // Quand a été modifié pour la dernière fois la partie pour changer l'étape ?
                $game = Game::find($request->game_id);
                $timeInMilliSeconds = Carbon::now()->diffInMilliseconds($game->updated_at);
                // La dernière modification de l'étape doit avoir été faite il y a plus de 2 secondes
                if ($timeInMilliSeconds >= 2000 && !$game->is_finished) {
                    if ($game->question_step + 1 < $game->question_count) {
                        // On modifie bien l'étape de la partie
                        $game->question_step = empty($game->question_step) ? 1 :  $game->question_step + 1;
                        $game->save();
                    }
                    // Si c'était la dernière question
                    else if ($game->question_step + 1 == $game->question_count) {
                        // Fin de partie
                        $game->question_step = $game->question_count;
                        $game->is_finished = true;
                        $game->save();
                        $endGame = true;
                    }
                }
            }
            // Création des résultats de la question
            GameResult::create($request->validated());
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
        return response()->json(['success' => true, 'message' => "C'est OK", 'endGame' => $endGame]);
    }

    /**
     * Display the specified resource.
     *
     * @param GameResult $gameResult
     * @return Response
     */
    public function show(GameResult $gameResult): Response
    {
        //
        return response($gameResult);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param GameResult $gameResult
     * @return Response
     */
    public function update(Request $request, GameResult $gameResult): Response
    {
        //
        return response($gameResult);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param GameResult $gameResult
     * @return Response
     */
    public function destroy(GameResult $gameResult): Response
    {
        //
        return response($gameResult);
    }
}
