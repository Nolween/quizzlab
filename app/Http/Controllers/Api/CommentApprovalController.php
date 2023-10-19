<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Approvals\CommentApprovalStoreRequest;
use App\Http\Resources\Approvals\CommentApprovalsStoreResource;
use App\Models\CommentApproval;
use App\Models\QuestionComment;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CommentApprovalController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Ajout des middleware nécessaire selon les actions
        // $this->middleware('log')->only('index');
        $this->middleware('auth:sanctum')->except(['show', 'index']);
    }

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
    public function store(CommentApprovalStoreRequest $request): CommentApprovalsStoreResource|JsonResponse
    {

        // Transaction pour rollback si erreur
        DB::beginTransaction();
        try {
            // Mise en place du vote, s'il existe déjà une ligne avec cet utilisateur et ce commentaire, update, sinon create
            CommentApproval::updateOrCreate(
                ['user_id' => auth()->id(), 'comment_id' => $request->commentid],
                ['has_approved' => $request->ispositive]
            );

            // Recalcul des réactions positives
            $positiveApprovals = CommentApproval::where('comment_id', $request->commentid)->where('has_approved', 1)->count();
            $negativeApprovals = CommentApproval::where('comment_id', $request->commentid)->where('has_approved', 0)->count();
            // MAJ des comptes pour le commentaire
            $commentUpdate = QuestionComment::findOrFail($request->commentid);
            $commentUpdate->approvals_count = $positiveApprovals;
            $commentUpdate->disapprovals_count = $negativeApprovals;
            // On sauvegarde sans toucher aux timestamps du commentaire, pour ne pas fausser le (modifié)
            $commentUpdate->timestamps = false;
            $commentUpdate->save();
            // Constitution du tableau de retour
            $response = [
                'ispositive' => $request->ispositive,
                'commentid' => $request->commentid,
                'approvals_count' => $positiveApprovals,
                'disapprovals_count' => $negativeApprovals,
            ];
            // Validation de la transaction
            DB::commit();

            // Retour dans le front des informations
            return new CommentApprovalsStoreResource($response);
        } // Si erreur dans la transaction
        catch (QueryException $e) {
            DB::rollback();

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CommentApproval $commentApproval): Response
    {
        //
        return response($commentApproval);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CommentApproval $commentApproval): Response
    {
        //
        return response($commentApproval);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommentApproval $commentApproval): Response
    {
        //
        return response($commentApproval);
    }
}
