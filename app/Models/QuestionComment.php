<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'user_id',
        'comment',
        'disapprovals',
        'approvals',
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

}
