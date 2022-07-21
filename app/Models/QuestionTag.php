<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * Quelle est la question?
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    /**
     * Quel est le thème?
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

}
