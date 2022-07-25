<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionIndexResource;
use App\Models\Question;
use Illuminate\Http\Request;

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
     * @return \App\Http\Resources\QuestionIndexResource
     */
    public function show(Question $question)
    {
        return new QuestionIndexResource($question);
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
