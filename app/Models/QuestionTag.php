<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionTag extends Model
{
    use HasFactory;


    // Pas besoin des created_at et updated_at
    public $timestamps = false;


    protected $fillable = [
        'tag_id',
        'question_id',
    ];


    /**
     * Quelle est la question ?
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Quel est le thème ?
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

}
