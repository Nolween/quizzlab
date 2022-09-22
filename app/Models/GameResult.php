<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * Quelle est la question appartient le résultat?
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * A quel joueur appartient le résultat de la question?
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
