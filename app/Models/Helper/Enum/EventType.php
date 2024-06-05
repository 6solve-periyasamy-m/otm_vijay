<?php

namespace App\Models\Helper\Enum;

enum EventType: int
{
    case NORMAL = 0;
    case MAIN = 1;

    public static function toArray(): array
    {
        $values = [];
        foreach (self::cases() as $case) {
            $values[$case->value] = $case->label();
        }
        return $values;
    }

    public function label(): string
    {
        return match ($this) {
            self::NORMAL => "Normal Event",
            self::MAIN => "Main Event",
        };
    }
}
