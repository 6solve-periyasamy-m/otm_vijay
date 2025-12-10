<?php

namespace App\Exceptions;

use App\Http\Helper\ApiErrorCode;

class NotOnTourException extends BookingApiException
{
    public function getErrorCode(): ApiErrorCode
    {
        return ApiErrorCode::NOT_ON_TOUR;
    }
}
