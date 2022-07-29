<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Http\Requests\Questions\QuestionVoteRequest;
use App\Http\Resources\QuestionIndexResource;
use App\Http\Resources\Questions\QuestionShowResource;
use App\Http\Resources\Questions\QuestionVoteResource;
use App\Models\Question;
use App\Models\QuestionVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Affiche la liste des questions
     * /api/questions
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QuestionIndexResource::collection(Question::paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\QuestionRequest  $request
     * @return \App\Http\Resources\QuestionIndexResource
     */
    public function store(QuestionRequest $request)
    {
        $question = Question::create($request->validated());

        return new QuestionIndexResource($question);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \App\Http\Resources\QuestionShowResource
     */
    public function show(Question $question)
    {
        return new QuestionShowResource($question);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\QuestionRequest  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionRequest $request, Question $question)
    {
        $question->update($request->validated());

        return new QuestionIndexResource($question);
    }

    /**
     * Voter pour ou contre une question
     *
     * @param  \App\Http\Requests\Questions\QuestionVoteRequest  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function vote(QuestionVoteRequest $request, Question $question)
    {
        $user = Auth::user();
        if (!$user) {
            dd('NOP');
        }
        // Mise en place du vote, si il existe déjà une ligne avec cet utilisateur et cette question, update, sinon create
        $questionVote = QuestionVote::updateOrCreate(
            ['user_id' => $user->id, 'question_id' => $question->id],
            ['has_approved' => $request->ispositive]
        );

        // Retour dans le front des informations
        return new QuestionVoteResource($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->delete();

        return response()->noContent();
    }
}
