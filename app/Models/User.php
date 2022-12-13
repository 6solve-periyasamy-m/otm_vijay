<?php

namespace App\Models;

use App\Models\System\ApiToken;
use App\Repository\Authentication\UserRepository;
use Database\Factories\UserFactory;
use Eloquent;
use Gravatar;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Silber\Bouncer\Database\Role;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $avatar
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $settings
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Ability[] $abilities
 * @property-read int|null $abilities_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Collection|ApiToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read string $avatar_url The URL for the avatar
 * @method static UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereAvatar($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereIs($role)
 * @method static Builder|User whereIsAll($role)
 * @method static Builder|User whereIsNot($role)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereSettings($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use \Illuminate\Auth\Authenticatable, HasFactory, Notifiable, HasRolesAndAbilities, SoftDeletes;

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

    protected string $guard = 'web';

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

    public function tokens(): HasMany
    {
        return $this->hasMany(ApiToken::class, 'user_id');
    }

    public function getCurrentToken(): ApiToken
    {
        return UserRepository::getLatestToken($this);
    }

    public function generateToken(int $expiresIn = ApiToken::DEFAULT_EXPIRY): ApiToken
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

    public function getHighestRoleLevel(): int
    {
        $highest = 0;
        foreach ($this->roles as $role) {
            $highest = $highest >= ($role->level ?? 0) ? $highest : $role->level;
        }
        return $highest;
    }

    public function getCurrentRole(): ?Role
    {
        $highest = null;
        foreach ($this->roles as $role) {
            $highest = isset($highest) && $highest->level >= $role->level ? $highest : $role;
        }
        return $highest;
    }

    public function getAvatarUrlAttribute(): string
    {
        if (isset($this->avatar)) return asset($this->avatar);
        return isset($this->email) ? Gravatar::get($this->email) : ('https://secure.gravatar.com/avatar/?d=mp&s=300');
    }
}
