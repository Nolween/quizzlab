<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameRule extends Model
{
    use HasFactory;


    // Pas besoin des created_at et updated_at
    public $timestamps = false;


    protected $fillable = [
        'name',
    ];

    /**
     * Quelles parties utilisent cette règle ?
     */
    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }
}
