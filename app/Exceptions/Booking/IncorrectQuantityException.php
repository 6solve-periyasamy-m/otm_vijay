<?php

namespace App\Exceptions\Booking;

use App\Exceptions\BookingApiException;
use App\Http\Helper\ApiErrorCode;

class IncorrectQuantityException extends BookingApiException
{
    public function getErrorCode(): ApiErrorCode
    {
        return ApiErrorCode::QUANTITY_EXCEEDED;
    }
}
