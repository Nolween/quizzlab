<?php

namespace App\Http\Resources\GameQuestions;

use App\Models\GameQuestion;
use App\Models\Tag;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class GameQuestionShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     */
    public function toArray(Request $request): array|JsonSerializable|Arrayable
    {
        $uniqueTags = $this->questionTags->unique('tag_id')->pluck('tag_id');
        $tags = Tag::whereIn('id', $uniqueTags)->pluck('name');
        $choices = $this->question->choicesWithoutCorrect->shuffle();

        // Récupération de tous les tags de la question
        // $tags = QuestionTag::with('tag')->where('question_id', $this->question->id)->get()->pluck('');
        return [
            'gameQuestionId' => $this->id,
            'isFinished'     => $this->game->is_finished,
            'questionStep'   => $this->game->question_step,
            'responseTime'   => $this->game->response_time,
            'question'       => $this->question->question,
            'questionId'     => $this->question->id,
            'gameId'         => $this->game->id,
            'image'          => $this->question->image,
            'tags'           => $tags,
            'choices'        => $choices,
        ];
    }
}
