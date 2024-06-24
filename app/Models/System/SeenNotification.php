<?php

namespace App\Models\System;

use App\Models\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * \App\Models\System\SeenNotification
 *
 * @property int $id
 * @property int $notification_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Notification $notification
 * @property-read User $user
 * @method static Builder|SeenNotification newModelQuery()
 * @method static Builder|SeenNotification newQuery()
 * @method static Builder|SeenNotification query()
 * @method static Builder|SeenNotification whereCreatedAt($value)
 * @method static Builder|SeenNotification whereId($value)
 * @method static Builder|SeenNotification whereNotificationId($value)
 * @method static Builder|SeenNotification whereUpdatedAt($value)
 * @method static Builder|SeenNotification whereUserId($value)
 * @mixin Eloquent
 */
class SeenNotification extends Model
{
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }
}
