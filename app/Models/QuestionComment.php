<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'comment_id',
    ];

    /**
     * À quelle question appartient le commentaire ?
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * À quel utilisateur appartient le commentaire ?
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Combien d'avis positifs sur ce commentaire ?
     */
    public function approvals(): HasMany
    {
        return $this->hasMany(CommentApproval::class, 'comment_id');
    }

    /**
     * Avis positifs sur ce commentaire ?
     */
    public function positiveApprovals(): HasMany
    {
        return $this->hasMany(CommentApproval::class, 'comment_id')->where('has_approved', true);
    }

    /**
     * Avis négatifs sur ce commentaire ?
     */
    public function negativeApprovals(): HasMany
    {
        return $this->hasMany(CommentApproval::class, 'comment_id')->where('has_approved', false);
    }

    /**
     * Indique l'avis de l'utilisateur sur le commentaire
     */
    public function userOpinion(): HasOne
    {
        return $this->hasOne(CommentApproval::class, 'comment_id')->ofMany([
            'updated_at' => 'max',
            'id' => 'max',
        ], function ($query) {
            $query->where('user_id', '=', Auth::id());
        });
    }

    public function responses(): HasMany
    {
        return $this->hasMany(QuestionComment::class, 'comment_id')->orderBy('created_at', 'ASC');
    }
}
