<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'question',
        'image',
        'is_integrated',
        'vote',
        'ratio_score',
    ];

    /**
     * À qui appartient la question
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quelles sont les parties qui utilisent cette question ?
     */
    public function games(): HasMany
    {
        return $this->hasMany(GameQuestion::class);
    }


    /**
     * Quels sont les thèmes de cette question ?
     */
    public function tags(): HasMany
    {
        return $this->hasMany(QuestionTag::class);
    }


    /**
     * Quels sont les commentaires de cette question ?
     */
    public function comments(): HasMany
    {
        return $this->hasMany(QuestionComment::class);
    }


    /**
     * Quels sont les commentaires de premier niveau de cette question ?
     */
    public function primary_comments(): HasMany
    {
        return $this->hasMany(QuestionComment::class)->where('comment_id', null)->orderBy('created_at', 'ASC');
    }

    /**
     * Quels sont les choix de cette question ?
     */
    public function choices(): HasMany
    {
        return $this->hasMany(QuestionChoice::class);
    }

    /**
     * Quels sont les choix de cette question, sans retourner la valeur correcte ?
     */
    public function choicesWithoutCorrect(): HasMany
    {
        return $this->hasMany(QuestionChoice::class)->select('id', 'title');
    }

}
