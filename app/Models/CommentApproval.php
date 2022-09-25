<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * À quel utilisateur appartient l'avis ?
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * À quel commentaire appartient l'avis ?
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(QuestionComment::class);
    }



}
