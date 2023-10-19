<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GamePlayer extends Model
{
    use HasFactory;

    // Pas besoin des created_at et updated_at
    public $timestamps = false;

    protected $fillable = [
        'game_id',
        'user_id',
        'is_ready',
        'final_score',
        'final_place',
    ];

    /**
     * Quel joueur est dans cette partie ?
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Dans quelle partie le joueur est-il ?
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
