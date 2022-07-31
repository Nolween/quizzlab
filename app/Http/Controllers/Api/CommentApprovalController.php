<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Approvals\CommentApprovalStoreRequest;
use App\Http\Resources\Approvals\CommentApprovalsStoreResource;
use App\Http\Resources\ErrorsResource;
use App\Models\CommentApproval;
use App\Models\QuestionComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * @param  \App\Http\Requests\Approvals\CommentApprovalStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentApprovalStoreRequest $request)
    {
        $user = Auth::user();

        // Transaction pour rollback si erreur
        DB::beginTransaction();
        try {
            // Mise en place du vote, si il existe déjà une ligne avec cet utilisateur et ce commentaire, update, sinon create
            $commentApproval = CommentApproval::updateOrCreate(
                ['user_id' => $user->id, 'comment_id' => $request->commentid],
                ['has_approved' => $request->ispositive]
            );

            // Recalcul des réactions positives
            $positiveApprovals = CommentApproval::where('comment_id', $request->commentid)->where('has_approved', 1)->get()->count();
            $negativeApprovals = CommentApproval::where('comment_id', $request->commentid)->where('has_approved', 0)->get()->count();
            // MAJ des comptes pour le commentaire
            $commentUpdate = QuestionComment::findOrFail($request->commentid);
            $commentUpdate->approvals_count = $positiveApprovals;
            $commentUpdate->disapprovals_count = $negativeApprovals;
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
        }
        // Si erreur dans la transaction
        catch (QueryException $e) {
            DB::rollback();
        }
        // Retour dans le front des informations
        return new CommentApprovalsStoreResource($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CommentApproval  $commentApproval
     * @return \Illuminate\Http\Response
     */
    public function show(CommentApproval $commentApproval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CommentApproval  $commentApproval
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommentApproval $commentApproval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CommentApproval  $commentApproval
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommentApproval $commentApproval)
    {
        //
    }
}
