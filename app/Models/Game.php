<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'user_id',
        'game_rule_id',
        'max_players',
        'response_time',
        'question_count',
        'has_begun',
        'is_finished',
        'game_code',
        'question_step'
    ];


    /**
     * Qui a créé cette partie?
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quelle est la règle de cette partie?
     */
    public function rule()
    {
        return $this->belongsTo(GameRule::class);
    }

    /**
     * Quel sont les joueurs de cette partie?
     */
    public function players()
    {
        return $this->hasMany(GamePlayer::class);
    }

    /**
     * Quel sont les discussions de cette partie?
     */
    public function chats()
    {
        return $this->hasMany(GameChat::class);
    }

    /**
     * Quel sont les questions de cette partie?
     */
    public function questions()
    {
        return $this->hasMany(GameQuestion::class);
    }

    /**
     * Quel sont les thèmes de cette partie?
     */
    public function tags()
    {
        return $this->hasMany(GameTag::class);
    }


}
