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

}
