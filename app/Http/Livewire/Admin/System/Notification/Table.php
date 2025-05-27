<?php

namespace App\Http\Livewire\Admin\System\Notification;

use Illuminate\Support\Collection;
use App\Models\System\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Helper\Enum\NotificationType;
use Livewire\Component;
use Settings;
use Illuminate\Support\Str;

class Table extends Component
{

    public function render()
    {
        $notifications = Notification::with([
            'actor', 'subject', 'resolver'
        ])->latest()->get()->reject(function ($notification) {
                return $notification->type === NotificationType::BOOKING_PROGRESS;
            });

        return view('livewire.admin.system.notification.table', [
            'data' => $this->getNotificationList($notifications),
        ]);
    }

    public function resolve($id): void
    {
        if ($n = Notification::find($id)) {
            $n->toggleResolved(Auth::user());
            $n->markSeen(Auth::user());
        }
    }

    public function seen($id): void
    {
        if ($n = Notification::find($id)) {
            $n->toggleSeen(Auth::user());
        }
    }

    public static function getNotificationList($notifications): array
    {
        $data = [];
        $userId = Auth::id();
        foreach ($notifications as $notification) {
            $row = collect();
            $row->id = $notification->id;
            $row->created_at = $notification->created_at->format('Y-m-d H:i:s');
            $actorDisplayName = 'System';
            if ($notification->actor) {
                if ($notification->actor instanceof \App\Models\User) {
                    $actorDisplayName = '(Admin) ' . $notification->actor->name;
                } elseif ($notification->actor instanceof \App\Models\Customer\Customer) {
                    $actorDisplayName = '(Customer) ' . $notification->actor->full_name;
                } else {
                    $actorDisplayName = 'Unknown';
                }
            }
            $row->actorName = $actorDisplayName;
            $row->type = $notification->type->label();
            $row->details = $notification->details;
            $row->subject = self::getSubjectLabel($notification->subject_type, $notification->subject_id);
            $object = $notification->subject_type::find($notification->subject_id);
            $row->eventName = $object ? $object?->tour?->event?->name : '';
            $row->PackageName = $object ? $object?->tour?->name : '';
            $row->totalOrderValue = $object?->total ? fr_currency($object->total, $object->currency?->code ?? 'GBP', true) : 'N/A';

            if (class_basename($notification->subject_type) === 'Booking') {
                $row->noOfTravellers = $object->travellers()->count();
                $row->firstName = $object->leadTraveller?->first_name;
                $row->lastName = $object->leadTraveller?->last_name;
                $row->email = $object->leadTraveller?->email_address;
                $row->contact = $object->leadTraveller?->mobile_number;
            } else {
                $row->noOfTravellers = $object?->customer_count;
                $row->firstName = $object?->leadBooker?->customer?->first_name;
                $row->lastName = $object?->leadBooker?->customer?->last_name;
                $row->email = $object?->leadBooker?->customer?->email_address;
                $row->contact = $object?->leadBooker?->mobile_number;
            }
            $row->seen = $notification->seenBy->contains($userId);
            $row->resolved_by = $notification->resolver?->name ?? '';

            $data[$notification->id] = $row;
        }
        return $data;
    }

    public static function getSubjectLabel(string $type, int|string $id): string
    {
        if (!class_exists($type)) {
            return 'Invalid Type';
        }
        $subject = $type::find($id);
        if (!$subject) {
            return 'Subject Deleted';
        }
        if (method_exists($subject, 'getLink')) {
            if (class_basename($type) === 'Booking') {
                return '<a style="line-height: 30px;" href="' . e($subject->getLink()) . '" target="_blank" class="text-blue-600 underline">' . e('View Booking') . '</a>';
            }
            return $subject->getLink();
        }
        return class_basename($type) . " #{$id}";
    }
}
