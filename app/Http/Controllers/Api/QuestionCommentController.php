<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comments\QuestionCommentStoreRequest;
use App\Http\Resources\Comments\QuestionCommentStoreResource;
use App\Models\QuestionComment;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\throwException;

class QuestionCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Comments\QuestionCommentStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionCommentStoreRequest $request)
    {
        $user = Auth::user();
        // Transaction pour rollback si erreur
        DB::beginTransaction();
        try {
            // Si c'est une réponse à une autre réponse de commentaire Id
            $isResponseComment = QuestionComment::where('id', $request->commentreplyid)->first()->comment_id ?? null;
            // Récupération de l'id du commentaire de premier niveau
            $commentId = !empty($isResponseComment) ? $isResponseComment : $request->commentreplyid;
            $newComment = new QuestionComment();
            $newComment->question_id = $request->questionid;
            $newComment->user_id = $user->id;
            $newComment->comment = $request->comment;
            $newComment->comment_id = $commentId;
            $newComment->save();
            // Validation de la transaction
            DB::commit();
        }
        // Si erreur dans la transaction
        catch (QueryException $e) {
            DB::rollback();
        }
        // Retour dans le front des informations
        return new QuestionCommentStoreResource($newComment);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuestionComment  $questionComment
     * @return \Illuminate\Http\Response
     */
    public function show(QuestionComment $questionComment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuestionComment  $questionComment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QuestionComment $questionComment)
    {
        $user = Auth::user();
        // Transaction pour rollback si erreur
        DB::beginTransaction();
        try {
            // On cherche le commentaire
            $comment = QuestionComment::where('id', $request->commentid)->firstOrFail();
            // On vérifie qu'il appartient à cet utilisateur
            if($comment->user_id !== $user->id) {
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
        }
        // Retour dans le front des informations
        return new QuestionCommentStoreResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuestionComment  $questionComment
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuestionComment $questionComment)
    {
        //
    }
}
