<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameResult extends Model
{
    use HasFactory;

    // Pas besoin des created_at et updated_at
    public $timestamps = false;


    protected $fillable = [
        'game_question_id',
        'user_id',
        'response',
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
