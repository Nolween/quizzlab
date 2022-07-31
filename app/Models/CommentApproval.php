<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentApproval extends Model
{
    use HasFactory;

    protected $fillable = 
    [
        'comment_id',
        'user_id',
        'has_approved'
    ];


    /**
     * A quel utilisateur appartient l'avis?
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * A quel commentaire appartient l'avis?
     */
    public function comment()
    {
        return $this->belongsTo(QuestionComment::class);
    }



}
