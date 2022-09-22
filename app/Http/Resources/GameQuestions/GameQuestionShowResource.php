<?php

namespace App\Http\Resources\GameQuestions;

use App\Models\QuestionTag;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\JsonResource;

class GameQuestionShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \App\Models\GameQuestion  $gameQuestion
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($gameQuestion)
    {
        $uniqueTags = $this->questionTags->unique('tag_id')->pluck('tag_id');
        $tags = Tag::whereIn('id', $uniqueTags)->get()->pluck('name');
        $choices = $this->question->choicesWithoutCorrect->shuffle();
        // Récupération de tous les tags de la question
        // $tags = QuestionTag::with('tag')->where('question_id', $this->question->id)->get()->pluck('');
        return [
            'gameQuestionId' => $this->id,
            'questionStep' => $this->game->question_step,
            'responseTime' => $this->game->response_time,
            'question' => $this->question->question,
            'questionId' => $this->question->id,
            'gameId' => $this->game->id,
            'image' => $this->question->image,
            'tags' => $tags,
            'choices' => $choices,
        ];
    }
}
