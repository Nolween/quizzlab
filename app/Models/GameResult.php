<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class GameResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_question_id',
        'user_id',
        'choice_id',
        'is_correct',
        'score',
    ];

    /**
     * Quelle est la question appartient le résultat ?
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * À quel joueur appartient le résultat de la question ?
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * À quelle question de partie appartient le résultat de la question ?
     */
    public function gameQuestion(): BelongsTo
    {
        return $this->belongsTo(GameQuestion::class, 'game_question_id', 'id');
    }

    /**
     * Quel est le choix du résultat ?
     */
    public function choice(): BelongsTo
    {
        return $this->belongsTo(QuestionChoice::class);
    }

    /**
     * À quelle partie appartient le résultat de la question ?
     */
    public function game(): HasOneThrough
    {
        return $this->hasOneThrough(Game::class,
            GameQuestion::class,
            'game_question_id',
            'game_id',
            'id',
            'id');
    }
}
