<?php

namespace App\Models\Helper;

use App\View\Components\Badge\Quote as QuoteBadge; // Prevents confusion
use Closure;
use Illuminate\Contracts\View\View;

enum QuoteStatus: int
{

    case EXPIRED = -1;
    case NOT_SENT = 0;
    case AWAITING = 1;
    case CHANGES = 2;
    case APPROVED = 3;
    case CONVERTED = 4;
    case CLOSED = 5;
    case UNKNOWN = 999;

    public function description(): string
    {
        return match ($this) {
            self::EXPIRED => trans('quotes.status.expired'),
            self::NOT_SENT => trans('quotes.status.not_sent'),
            self::CHANGES => trans('quotes.status.changes'),
            self::AWAITING => trans('quotes.status.awaiting'),
            self::APPROVED => trans('quotes.status.approved'),
            self::CONVERTED => trans('quotes.status.converted'),
            self::CLOSED => trans('quotes.status.closed'),
            self::UNKNOWN => trans('quotes.status.unknown'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::EXPIRED => 'danger',
            self::NOT_SENT => 'info',
            self::AWAITING => 'light',
            self::CHANGES => 'warning',
            self::APPROVED => 'primary',
            self::CONVERTED => 'success',
            self::CLOSED => 'secondary',
            self::UNKNOWN => 'dark',
        };
    }

    public function badge(): View|Closure|string
    {
        return (new QuoteBadge($this))->render();
    }
}
