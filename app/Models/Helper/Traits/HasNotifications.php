<?php

namespace App\Models\Helper\Traits;

use App\Models\Helper\Enum\NotificationType;
use App\Models\System\Notification;
use Auth;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @method morphMany(string $class, string $string)
 */
trait HasNotifications
{
    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'subject');
    }

    public function createNotification(NotificationType $type, string $details): Notification
    {
        $notification = Notification::make([
            'type' => $type,
            'details' => $details,
        ]);
        $notification->actor()->associate(Auth::user());
        $notification->subject()->associate($this);
        $notification->save();
        return $notification;
    }
}
