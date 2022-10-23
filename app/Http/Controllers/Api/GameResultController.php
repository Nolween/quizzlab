<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameResults\GameResultStoreRequest;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\GameQuestion;
use App\Models\GameResult;
use App\Models\QuestionChoice;
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
            $game = Game::find($request->game_id);
            // Déjà des résultats envoyés pour cette question ?
            $noResults = GameResult::where('game_question_id', $request->game_question_id)->doesntExist();
            if ($noResults) {
                // Quand a été modifié pour la dernière fois la partie pour changer l'étape ?
                $timeInMilliSeconds = Carbon::now()->diffInMilliseconds($game->updated_at);
                // La dernière modification de l'étape doit avoir été faite il y a plus de 2 secondes
                if ($timeInMilliSeconds >= 2000 && !$game->is_finished) {
                    if ($game->question_step + 1 < $game->question_count) {
                        // On modifie bien l'étape de la partie
                        $game->question_step = empty($game->question_step) ? 1 : $game->question_step + 1;
                        $game->save();
                    } // Si c'était la dernière question
                    else if ($game->question_step + 1 == $game->question_count) {
                        // Fin de partie
                        $game->question_step = $game->question_count;
                        $game->is_finished = true;
                        $game->save();
                        $endGame = true;
                    }
                }
            }
            // Si on a bien une réponse envoyée
            if ($request->choice_id) {
                // Bonne réponse à la question ?
                $questionId = GameQuestion::find($request->game_question_id)->question->id;
                $rightChoiceId = QuestionChoice::where('question_id', $questionId)->where('is_correct', true)->first()->id;
                $isCorrect = $rightChoiceId === $request->choice_id;
                $request->request->add(['is_correct' => $isCorrect]);
                if ($isCorrect === true) {
                    $score = GameQuestion::find($request->game_question_id)->question->ratio_score;
                }
                $request->request->add(['score' => $isCorrect ? $score : 0]);
                // Création des résultats de la question
                GameResult::updateOrCreate(
                    ['user_id' => auth()->id(), 'game_question_id' => $request->game_question_id],
                    $request->validated()
                );
            }

            // Si la partie est finie, établissement du classement
            if ($endGame === true || $game->is_finished === true) {
                $orderedPlayers = GamePlayer::where('game_id', $game->id)->orderBy('final_score', 'DESC')->get();
                $order = 1;
                foreach ($orderedPlayers as $orderedPlayer) {
                    $orderedPlayer->final_place = $order;
                    $orderedPlayer->save();
                    $order++;
                }
            }
            DB::commit();

        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }

        //    Mise à jour du nombre de points pour le joueur
        // Quelles sont les questions de la partie ?
        $gameQuestionsIds = GameQuestion::where('game_id', $game->id)->get()->pluck('id');
        // Récupération du total de score des résultats de la partie du joueur
        $userPoints = GameResult::whereIn('game_question_id', $gameQuestionsIds)->where('user_id', auth()->id())->sum('score');
        $gamePlayer = GamePlayer::where('game_id', $game->id)->where('user_id', auth()->id())->first();
        $gamePlayer->final_score = $userPoints;
        $gamePlayer->save();
        return response()->json(['success' => true, 'endGame' => $endGame === true || $game->is_finished === true]);
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
