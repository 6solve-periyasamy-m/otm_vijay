<?php

namespace App\Models\Helper;

enum QuoteStatus: int
{

    case EXPIRED = -1;
    case NOT_SENT = 0;
    case AWAITING = 1;
    case APPROVED = 2;
    case CONVERTED = 3;
    case CLOSED = 4;

    public function description(): string
    {
        return match ($this) {
            self::EXPIRED => trans('quotes.status.expired'),
            self::NOT_SENT => trans('quotes.status.not_sent'),
            self::AWAITING => trans('quotes.status.awaiting'),
            self::APPROVED => trans('quotes.status.approved'),
            self::CONVERTED => trans('quotes.status.converted'),
            self::CLOSED => trans('quotes.status.closed'),
        };
    }
}
