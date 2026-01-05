<?php

namespace App\Http\Helper;

enum ApiErrorCode: string
{
    case NOT_ON_TOUR = 'NOT_ON_TOUR';
    case COMPONENT_NOT_FOUND = 'COMPONENT_NOT_FOUND';
    case QUANTITY_EXCEEDED = 'QUANTITY_EXCEEDED';
    case ROOM_SIZE_TOO_SMALL = 'ROOM_SIZE_TOO_SMALL';

}
