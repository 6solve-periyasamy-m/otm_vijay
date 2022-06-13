<?php

namespace App\Repository;

use App\Models\Activity\ActivityInventoryTour;
use App\Models\Booking\Booking;
use Illuminate\Support\Facades\DB;
use Log;
use Throwable;

class CustomerBookingRepository
{
    /**
     * @throws Throwable
     */
    public static function upgradeBookingActivity(Booking $booking, ActivityInventoryTour $from, ActivityInventoryTour $to): bool
    {
        if (($booking->tour_id !== $from->tour_id) || ($booking->tour_id !== $to->tour_id)) return false;
        if (!$to->is_bookable) return false;
        if ($to->available_stock < $booking->travellers()->count()) return false;
        try {
            DB::beginTransaction();
            foreach ($booking->travellers as $traveller) {
                DB::table('booking_activities')
                    ->where('booking_traveller_id', '=', $traveller->id)
                    ->where('activity_inventory_tour_id', '=', $from->id)
                    ->update(['activity_inventory_tour_id' => $to->id,]);
            }
            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();
            return false;
        }
        return true;
    }
}
