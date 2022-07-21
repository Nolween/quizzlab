<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'user_id',
        'has_approved'
    ];

    
    /**
     * A quel question correspond le vote?
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * A quel utilisateur correspond le vote?
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
