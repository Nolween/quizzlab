<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Quel est le rôle de l'utilisateur?
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Quelles questions l'utilisateur a t-il proposées?
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Quelles parties l'utilisateur a t-il crées?
     */
    public function games()
    {
        return $this->hasMany(Game::class);
    }

    /**
     * Quelles discussion de partie l'utilisateur a t-il envoyées?
     */
    public function chats()
    {
        return $this->hasMany(GameChat::class);
    }

    /**
     * Quelles résultats de questions l'utilisateur a t-il envoyées?
     */
    public function results()
    {
        return $this->hasMany(GameResult::class);
    }

    /**
     * A quelles parties l'utilisateur a t-il participé?
     */
    public function plays()
    {
        return $this->hasMany(GamePlayer::class);
    }

}
