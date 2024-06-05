<?php

namespace App\Models\Helper\Enum;

use App\Models\Helper\Enum\Trait\ConvertsToArray;

enum ActivityCategory: int
{
    use ConvertsToArray { label as protected trait_label; }

    case NORMAL = 0;
    case MAIN = 1;


    public function label(): string
    {
        return match ($this) {
            self::NORMAL => "Normal Activity",
            self::MAIN => "Main Activity",
        };
    }

}
