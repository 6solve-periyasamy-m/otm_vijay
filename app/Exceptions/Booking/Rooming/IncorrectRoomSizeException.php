<?php

namespace App\Exceptions\Booking\Rooming;

use App\Exceptions\BookingApiException;
use App\Http\Helper\ApiErrorCode;

class IncorrectRoomSizeException extends BookingApiException
{
    public function getErrorCode(): ApiErrorCode
    {
        return ApiErrorCode::ROOM_SIZE_TOO_SMALL;
    }
}
