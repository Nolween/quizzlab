<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class QuestionComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'user_id',
        'comment',
        'disapprovals_count',
        'approvals_count',
        'comment_id'
    ];


    /**
     * A quelle question appartient le commentaire?
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * A quel utilisateur appartient le commentaire?
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Combien d'avis positifs sur ce commentaire?
     */
    public function approvals()
    {
        return $this->hasMany(CommentApproval::class, 'comment_id');
    }


    /**
     * Avis positifs sur ce commentaire?
     */
    public function positiveApprovals()
    {
        return $this->hasMany(CommentApproval::class, 'comment_id')->where('has_approved', true);
    }

    /**
     * Avis négatifs sur ce commentaire?
     */
    public function negativeApprovals()
    {
        return $this->hasMany(CommentApproval::class, 'comment_id')->where('has_approved', false);
    }


    /**
     * Indique l'avis de l'utilisateur sur le commentaire
     *
     * @return void
     */
    public function userOpinion()
    {
        return $this->hasOne(CommentApproval::class, 'comment_id')->ofMany([
            'updated_at' => 'max',
            'id' => 'max',
        ], function ($query) {
            $query->where('user_id', '=', Auth::id());
        });
    }

}
