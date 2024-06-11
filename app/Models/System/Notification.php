<?php

namespace App\Models\System;

use App\Models\Customer\Customer;
use App\Models\Helper\Enum\NotificationType;
use App\Models\Helper\NotificationSubject;
use App\Models\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * \App\Models\System\Notification
 *
 * @property int $id
 * @property string $actor_type
 * @property int $actor_id
 * @property string $subject_type
 * @property int $subject_id
 * @property string $details
 * @property NotificationType $type
 * @property int|null $resolved_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Customer|User $actor
 * @property-read User|null $resolver
 * @property-read NotificationSubject $subject
 * @property-read Collection<int, User> $seenBy
 * @property-read int|null $seen_by_count
 * @property-read Collection<int, SeenNotification> $seenNotifications
 * @property-read int|null $seen_notifications_count
 * @method static Builder|Notification whereDetails($value)
 * @method static Builder|Notification whereResolvedBy($value)
 * @method static Builder|Notification whereSubjectId($value)
 * @method static Builder|Notification whereSubjectType($value)
 * @method static Builder|Notification newModelQuery()
 * @method static Builder|Notification newQuery()
 * @method static Builder|Notification query()
 * @method static Builder|Notification whereActionedBy($value)
 * @method static Builder|Notification whereActorId($value)
 * @method static Builder|Notification whereActorType($value)
 * @method static Builder|Notification whereCreatedAt($value)
 * @method static Builder|Notification whereId($value)
 * @method static Builder|Notification whereNotifiableId($value)
 * @method static Builder|Notification whereNotifiableType($value)
 * @method static Builder|Notification whereType($value)
 * @method static Builder|Notification whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Notification extends Model
{
    protected $casts = ['type' => NotificationType::class,];
    protected $guarded = [];

    public function actor(): MorphTo
    {
        return $this->morphTo('actor');
    }

    public function subject(): MorphTo
    {
        return $this->morphTo('subject');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function seenNotifications(): HasMany
    {
        return $this->hasMany(SeenNotification::class, 'notification_id');
    }

    public function seenBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'seen_notifications', 'notification_id', 'user_id');
    }

    public function seen(User $user): SeenNotification|null
    {
        /** @var SeenNotification|null */
        return $this->seenNotifications()->where('user_id', '=', $user->id)->first();
    }

    public function markSeen(User $user): void
    {
        if ($this->seen($user) === null) {
            $this->seenBy()->attach($user);
        }
    }

    public function markUnseen(User $user): void
    {
        $this->seen($user)?->delete();
    }
}
