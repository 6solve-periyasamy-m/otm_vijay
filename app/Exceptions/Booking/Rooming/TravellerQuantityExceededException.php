<?php

namespace App\Exceptions\Booking\Rooming;

use App\Exceptions\BookingApiException;
use App\Http\Helper\ApiErrorCode;

class TravellerQuantityExceededException extends BookingApiException
{
    public function getErrorCode(): ApiErrorCode
    {
        return ApiErrorCode::QUANTITY_EXCEEDED;
    }
}
