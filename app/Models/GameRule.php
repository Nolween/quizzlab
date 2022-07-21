<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameRule extends Model
{
    use HasFactory;

    
    // Pas besoin des created_at et updated_at
    public $timestamps = false;


    protected $fillable = [
        'name',
    ];

    /**
     * Quelle parties utilisent cette règle?
     */
    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
