<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Booking\PaymentSchedule;
use Illuminate\Support\Facades\Log;

class PaymentController extends ApiController
{

    public function getPaymentSchedules()
    {
        $schedules = PaymentSchedule::orderBy('name')->get();

        return response()->json(["success" => true, "schedules" => $schedules->toArray()]);
    }

    public function getPaymentSchedule($id) 
    {
        $schedule = PaymentSchedule::findOrFail($id);
        if ($this->logging) Log::debug('schedule for id '.$id, $schedule->toArray());

        return response()->json(["success" => true, "schedule" => $schedule->toArray()]);
    }
}
