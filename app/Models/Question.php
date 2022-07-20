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
}
