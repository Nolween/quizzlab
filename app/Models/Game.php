<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'questions_have_all_tags',
        'question_step'
    ];


    /**
     * Qui a créé cette partie ?
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quelle est la règle de cette partie ?
     */
    public function rule(): BelongsTo
    {
        return $this->belongsTo(GameRule::class);
    }

    /**
     * Quel sont les joueurs de cette partie ?
     */
    public function players(): HasMany
    {
        return $this->hasMany(GamePlayer::class);
    }

    /**
     * Quel sont les discussions de cette partie ?
     */
    public function chats(): HasMany
    {
        return $this->hasMany(GameChat::class);
    }

    /**
     * Quel sont les questions de cette partie ?
     */
    public function questions(): HasMany
    {
        return $this->hasMany(GameQuestion::class);
    }

    /**
     * Quel sont les thèmes de cette partie ?
     */
    public function tags(): HasMany
    {
        return $this->hasMany(GameTag::class);
    }


}
