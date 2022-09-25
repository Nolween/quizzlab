<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class GameQuestion extends Model
{
    use HasFactory;

    // Pas besoin des created_at et updated_at
    public $timestamps = false;


    protected $fillable = [
        'game_id',
        'question_id',
        'order',
    ];


    /**
     * Dans quelle partie la question est-elle ?
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }


    /**
     * Quelle est la question ?
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Quels sont les tags de la question de la partie ?
     */
    public function questionTags(): HasManyThrough
    {
        return $this->hasManyThrough(QuestionTag::class,Question::class, 'id', 'question_id', 'id', 'id');
    }

}
