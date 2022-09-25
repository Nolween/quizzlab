<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_question_id',
        'user_id',
        'choice_id',
        'is_correct',
        'score'
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

}
