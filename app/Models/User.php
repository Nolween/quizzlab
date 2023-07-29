<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'avatar',
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
        'role' => UserRoleEnum::class,
    ];

    /**
     * Quelles questions l'utilisateur a-t-il proposées ?
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Quelles parties l'utilisateur a-t-il créées ?
     */
    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    /**
     * Quelles discussions de partie l'utilisateur a-t-il envoyées ?
     */
    public function chats(): HasMany
    {
        return $this->hasMany(GameChat::class);
    }

    /**
     * Quels résultats de questions l'utilisateur a-t-il envoyées ?
     */
    public function results(): HasMany
    {
        return $this->hasMany(GameResult::class);
    }

    /**
     * À quelles parties l'utilisateur a-t-il participé ?
     */
    public function plays(): HasMany
    {
        return $this->hasMany(GamePlayer::class);
    }

    /**
     * Quels sont les commentaires de l'utilisateur ?
     */
    public function comments(): HasMany
    {
        return $this->hasMany(QuestionComment::class);
    }

    /**
     * Quels sont les votes de l'utilisateur ?
     */
    public function votes(): HasMany
    {
        return $this->hasMany(QuestionVote::class);
    }

}
