<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * Dans quelle partie la question est-elle?
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
    
    /**
     * Quelle est la question?
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

}
