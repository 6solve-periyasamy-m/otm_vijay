<?php

namespace App\Exceptions\Booking;

use App\Exceptions\BookingApiException;
use App\Http\Helper\ApiErrorCode;

class ComponentNotFoundException extends BookingApiException
{
    public function getErrorCode(): ApiErrorCode
    {
        return ApiErrorCode::COMPONENT_NOT_FOUND;
    }
}
