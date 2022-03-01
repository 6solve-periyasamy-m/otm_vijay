<?php

namespace App\Models;

use App\Repository\UserRepository;
use App\Rules\EmailCurrentOrUnique;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Authenticatable implements MustVerifyEmail
{
    use \Illuminate\Auth\Authenticatable, HasFactory, Notifiable, HasRolesAndAbilities;

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

    protected $guard = 'web';

    public function getUpdateValidationRules(): array
    {
        return [
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::unique('users', 'email')->ignore($this->id),
            ],
            'name' => 'required',
            'current_password' => 'nullable|required_with:new_password|current_password:web',
            'new_password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ]
        ];
    }

    public static function getCreateValidationRules(): array
    {
        return [
            'email' => [
                'required',
                'email:rfc,dns',
                'unique:users,email'
            ],
            'name' => 'required',
            'password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ]
        ];
    }

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
        UserRepository::invalidateAllUserTokens($this);
    }

    public function purgeTokens(int $limit = ApiToken::DEFAULT_LIMIT)
    {
        UserRepository::purgeUserTokens($this, $limit);
    }

    public function getHighestRoleLevel()
    {
        $highest = 0;
        foreach ($this->roles as $role) {
            $highest = $highest >= ($role->level ?? 0) ? $highest : $role->level;
        }
        return $highest;
    }

    public function getCurrentRole()
    {
        $highest = null;
        foreach ($this->roles as $role) {
            $highest = isset($highest) && $highest->level >= $role->level ? $highest : $role;
        }
        return $highest;
    }
}
