<?php

namespace App\Models;

use App\Repository\UserRepository;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use \Illuminate\Auth\Authenticatable, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tokens()
    {
        return $this->hasMany(ApiToken::class, 'user_id');
    }

    public function getCurrentToken()
    {
        return UserRepository::getLatestToken($this);
    }

    public function generateToken(int $expiresIn = ApiToken::DEFAULT_EXPIRY)
    {
        return UserRepository::generateUserToken($this, $expiresIn);
    }

    public function invalidateAllTokens()
    {
        foreach ($this->tokens as $token) {
            if (!$token->hasExpired()) {
                $token->invalidate();
            }
        }
    }

    public function purgeTokens(int $hours = ApiToken::DEFAULT_LIMIT)
    {
        foreach ($this->tokens as $token) {
            if (now()->addHours($hours*-1)->isAfter($token->expiry)) {
                $token->forceDelete();
            }
        }
    }
}
