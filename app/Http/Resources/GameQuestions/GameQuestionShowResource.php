<?php

namespace App\Http\Resources\GameQuestions;

use App\Models\GameQuestion;
use App\Models\Tag;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class GameQuestionShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param GameQuestion $gameQuestion
     */
    public function toArray($gameQuestion): array|JsonSerializable|Arrayable
    {
        $uniqueTags = $gameQuestion->questionTags->unique('tag_id')->pluck('tag_id');
        $tags = Tag::whereIn('id', $uniqueTags)->pluck('name');
        $choices = $gameQuestion->question->choicesWithoutCorrect->shuffle();

        // Récupération de tous les tags de la question
        // $tags = QuestionTag::with('tag')->where('question_id', $this->question->id)->get()->pluck('');
        return [
            'gameQuestionId' => $gameQuestion->id,
            'isFinished'     => $gameQuestion->game->is_finished,
            'questionStep'   => $gameQuestion->game->question_step,
            'responseTime'   => $gameQuestion->game->response_time,
            'question'       => $gameQuestion->question->question,
            'questionId'     => $gameQuestion->question->id,
            'gameId'         => $gameQuestion->game->id,
            'image'          => $gameQuestion->question->image,
            'tags'           => $tags,
            'choices'        => $choices,
        ];
    }
}
