<?php

namespace App\Models\Helper\Traits;

use App\Models\Customer\Customer;
use App\Models\Helper\Enum\NotificationType;
use App\Models\System\Notification;
use App\Models\User;
use Auth;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @method morphMany(string $class, string $string)
 */
trait HasNotifications
{
    public function systemNotifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'subject');
    }

    public function createNotification(NotificationType $type, string $details, User|Customer|null $actor = null): Notification
    {

        $notification = Notification::make([
            'type' => $type,
            'details' => $details,
        ]);
        $notification->actor()->associate($actor ?? Auth::user());
        $notification->subject()->associate($this);
        $notification->save();
        return $notification;
    }

    public function updateBookingProgressNotification(string $step, User|Customer|null $actor = null): Notification
    {
        $details = "Booking is in progress. Last completed step: $step";

        $existing = Notification::where('type', NotificationType::BOOKING_PROGRESS)
            ->where('subject_type', get_class($this))
            ->where('subject_id', $this->id)
            ->first();

        if ($existing) {
            $existing->details = $details;
            $existing->actor()->associate($actor ?? Auth::user());
            $existing->save();
            return $existing;
        }

        return $this->createNotification(NotificationType::BOOKING_PROGRESS, $details, $actor);
    }


}
