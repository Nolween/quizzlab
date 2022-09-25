<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'user_id',
        'text'
    ];


    /**
     * Qui a écrit le message ?
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Dans quelle partie a été écrite le message ?
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

}
