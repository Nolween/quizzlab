<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    // Pas besoin des created_at et updated_at
    public $timestamps = false;


    protected $fillable = [
        'name',
    ];


    /**
     * Quelles parties utilisent ce thème?
     */
    public function games()
    {
        return $this->hasMany(GameTag::class);
    }

    /**
     * Quelles questions utilisent ce thème?
     */
    public function questions()
    {
        return $this->hasMany(QuestionTag::class);
    }
}
