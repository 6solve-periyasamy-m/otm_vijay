<?php

namespace App\Models\Helper\Enum;

enum EventContentType: int
{
    case FAQ = 1;
    case IMPORTANT_INFO = 2;

    public function label(): string
    {
        return match ($this) {
            self::FAQ => "FAQ'S",
            self::IMPORTANT_INFO => "Important Information",
        };
    }
}
