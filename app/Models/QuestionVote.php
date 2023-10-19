<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'user_id',
        'has_approved',
    ];

    /**
     * À quelle question correspond le vote ?
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * À quel utilisateur correspond le vote ?
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
