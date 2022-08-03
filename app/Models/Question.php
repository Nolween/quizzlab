<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'question',
        'answer',
        'image',
        'is_integrated',
        'vote',
        'ratio_score',
    ];

    /**
     * A qui appartient la question
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quelles sont les parties qui utilisent cette question?
     */
    public function games()
    {
        return $this->hasMany(GameQuestion::class);
    }


    /**
     * Quels sont les thèmes de cette question?
     */
    public function tags()
    {
        return $this->hasMany(QuestionTag::class);
    }


    /**
     * Quels sont les commentaires de cette question?
     */
    public function comments()
    {
        return $this->hasMany(QuestionComment::class);
    }

    /**
     * Quels sont les commentaires de premier niveau de cette question?
     */
    public function primary_comments()
    {
        return $this->hasMany(QuestionComment::class)->where('comment_id', null)->orderBy('created_at', 'ASC');
    }
}
