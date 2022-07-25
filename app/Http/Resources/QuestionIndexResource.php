<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        // Conversion de la date de création
        Carbon::setLocale('fr');
        $ago = $this->created_at->diffForHumans(Carbon::now(), true);
        // Récupération des tags de la question
        $tagArray = [];
        $tags = $this->tags;
        foreach ($tags as $tag) {
            $tagArray[] = ['id' => $tag->tag->id, 'name' => $tag->tag->name];
        }
        return [
            'id' => $this->id,
            'question' => $this->question,
            'question' => $this->question,
            'answer' => $this->answer,
            'vote' => $this->vote,
            'avatar' => $this->user->avatar,
            'userName' => $this->user->name,
            'tags' => $tagArray,
            'commentsCount' => $this->comments->count(),
            'ago' => $ago,
        ];
        // return parent::toArray($request);
    }
}
