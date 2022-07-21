<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'user_id',
        'text'
    ];


    /**
     * Qui a écrit le message?
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Dans quelle partie a été écrit le message?
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

}
