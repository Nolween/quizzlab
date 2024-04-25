<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comments\QuestionCommentStoreRequest;
use App\Http\Resources\Comments\QuestionCommentStoreResource;
use App\Models\QuestionComment;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class QuestionCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        //
        return response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestionCommentStoreRequest $request): JsonResponse|QuestionCommentStoreResource
    {
        // Transaction pour rollback si erreur
        DB::beginTransaction();
        try {
            // Si c'est une réponse à une autre réponse de commentaire Id
            $isResponseComment = QuestionComment::where('id', $request->commentreplyid)->first()->comment_id ?? null;
            // Récupération de l'id du commentaire de premier niveau
            $commentId = ! empty($isResponseComment) ? $isResponseComment : $request->commentreplyid;
            $newComment = new QuestionComment();
            $newComment->question_id = $request->questionid;
            $newComment->user_id = auth()->id();
            $newComment->comment = $request->comment;
            $newComment->comment_id = $commentId;
            $newComment->save();
            // Validation de la transaction
            DB::commit();
        }
        // Si erreur dans la transaction
        catch (QueryException $e) {
            DB::rollback();

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        // Retour dans le front des informations
        return new QuestionCommentStoreResource($newComment);
    }

    /**
     * Display the specified resource.
     */
    public function show(QuestionComment $questionComment): Response
    {
        //
        return response($questionComment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): JsonResponse|QuestionCommentStoreResource
    {
        // Transaction pour rollback si erreur
        DB::beginTransaction();
        try {
            // On cherche le commentaire
            $comment = QuestionComment::where('id', $request->commentid)->firstOrFail();
            // On vérifie qu'il appartient à cet utilisateur
            if ($comment->user_id !== auth()->id()) {
                throw new Exception();
            }
            $comment->comment = $request->comment;
            $comment->save();
            // Validation de la transaction
            DB::commit();
        }
        // Si erreur dans la transaction
        catch (Exception $e) {
            DB::rollback();

            return response()->json(['message' => $e->getMessage().' >>> '.$e->getLine()], 500);
        }

        // Retour dans le front des informations
        return new QuestionCommentStoreResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuestionComment $questionComment): Response
    {
        //
        return response($questionComment);
    }
}
