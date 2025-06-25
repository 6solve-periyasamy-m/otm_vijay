<?php

namespace App\Models;

use App\Models\Order\Order;
use App\Models\Quote\Quote;
use App\Models\System\ApiToken;
use App\Models\System\Notification;
use App\Models\System\SeenNotification;
use App\Repository\Authentication\UserRepository;
use Database\Factories\UserFactory;
use Eloquent;
use Exception;
use Google2FA;
use Gravatar;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as UserAuthenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Silber\Bouncer\Database\Role;
use App\Models\Helper\Enum\NotificationType;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $avatar
 * @property string|null $telephone
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $otp_secret
 * @property string|null $remember_token
 * @property string|null $settings
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read int|null $orders_count
 * @property-read int|null $quotes_count
 * @property-read Collection<int, SeenNotification> $seenNotifications
 * @property-read int|null $seen_notifications_count
 * @method static Builder|User onlyTrashed()
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereOtpSecret($value)
 * @method static Builder|User whereTelephone($value)
 * @method static Builder|User withTrashed()
 * @method static Builder|User withoutTrashed()
 * @property-read Collection|Ability[] $abilities
 * @property-read int|null $abilities_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Collection|ApiToken[] $tokens
 * @property-read Collection|Order[] $orders
 * @property-read Collection|Quote[] $quotes
 * @property-read int|null $tokens_count
 * @property-read string $avatar_url The URL for the avatar
 * @property-read UserRepository $repository
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
class User extends UserAuthenticatable implements MustVerifyEmail
{
    use Authenticatable, HasFactory, Notifiable, HasRolesAndAbilities, SoftDeletes;

    protected $guarded = [];

    protected $hidden = ['password', 'remember_token', ];
    protected $casts = ['email_verified_at' => 'datetime',];
    protected string $guard = 'web';
    private UserRepository $internal_repository;

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

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'consultant_id');
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class, 'consultant_id');
    }

    public function getCurrentToken(): ApiToken
    {
        return $this->repository->getLatestToken();
    }

    public function generateToken(int $expiresIn = ApiToken::DEFAULT_EXPIRY): ApiToken
    {
        return $this->repository->generateToken($expiresIn);
    }

    public function invalidateAllTokens(): void
    {
        $this->repository->invalidateAllUserTokens();
    }

    public function purgeTokens(int $limit = ApiToken::DEFAULT_LIMIT): void
    {
        $this->repository->purgeUserTokens($limit);
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

    public function getTwoFactorUrl(string $secret): string
    {
        return Google2FA::getQRCodeUrl(config('auth.google-2fa.company'), $this->email, $secret);
    }

    public function verifyOneTimeCode(string|null $code): bool
    {
        if ($this->otp_secret === null) return true;
        if ( $code === null) return false;
        try {
            return Google2FA::verify($code, $this->otp_secret);
        } catch (Exception $e) {
            return false;
        }
    }

    public function isEditable(User|null $user = null): bool
    {
        return $this->isActionable($user, 'update');
    }

    public function isDeletable(User|null $user = null): bool
    {
        return $this->isActionable($user, 'delete', false);
    }

    public function isRecoverable(User|null $user = null): bool
    {
        return $this->isActionable($user, 'delete', false) && UserRepository::getRemainingUserCount();
    }

    private function isActionable(User|null $user, string $action, bool $self = true): bool
    {
        if ($user === null) return false;
        if ($user->id === $this->id) return $self;
        if ($user->can($action, static::class) &&
            $user->getHighestRoleLevel() > $this->getHighestRoleLevel()) return true;
        return false;
    }

    public function isOtm(): bool
    {
        return $this->getHighestRoleLevel() >= 999;
    }

    public function getAvatarUrlAttribute(): string
    {
        if (isset($this->avatar)) return asset($this->avatar);
        return isset($this->email) ? Gravatar::get($this->email) : ('https://secure.gravatar.com/avatar/?d=mp&s=300');
    }

    public function getRepositoryAttribute(): UserRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new UserRepository($this);
        return $this->internal_repository;
    }

    public function seenNotifications(): HasMany
    {
        return $this->hasMany(SeenNotification::class, 'user_id');
    }

    public function unseen(): int
    {
        return Notification::where('type', NotificationType::ORDER_CREATED)
            ->whereNotIn('id', $this->seenNotifications()->pluck('notification_id'))
            ->count();
    }
}
