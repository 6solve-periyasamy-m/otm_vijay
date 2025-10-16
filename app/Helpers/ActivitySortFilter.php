<?php

namespace App\Helpers;

enum ActivitySortFilter: int
{
    case MAIN_ACTIVITY = 0;
    case INCLUSION = 1;
    case ALL = 2;

    public function label(): string
    {
        return match ($this) {
            self::MAIN_ACTIVITY => __('Main Activity'),
            self::INCLUSION => __('Inclusion'),
            self::ALL => __('All'),
        };
    }

    public static function toArray(): array
    {
        $data = [];
        foreach (self::cases() as $case) {
            $data[$case->value] = $case->label();
        }
        return $data;
    }
}
