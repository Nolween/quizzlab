<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionChoice extends Model
{
    use HasFactory;

    // Pas besoin des created_at et updated_at
    public $timestamps = false;

    protected $fillable = [
        'question_id',
        'title',
        'is_correct'
    ];

    /**
     * À quelle question appartient le choix
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }


}
