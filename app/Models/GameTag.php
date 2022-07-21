<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * A quelle partie appartient l'association?
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * A quelle thème appartient l'association?
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
