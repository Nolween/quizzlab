<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameTag extends Model
{
    use HasFactory;

    // Pas besoin des created_at et updated_at
    public $timestamps = false;

    protected $fillable = [
        'tag_id',
        'game_id',
    ];

    /**
     * À quelle partie appartient l'association ?
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * À quel thème appartient l'association ?
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
