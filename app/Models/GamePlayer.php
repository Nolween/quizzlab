<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
