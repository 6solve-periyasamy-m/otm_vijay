<?php

namespace App\Exceptions;

use App\Http\Helper\ApiErrorCode;
use Exception;

abstract class BookingApiException extends Exception
{
    abstract public function getErrorCode(): ApiErrorCode;
}
