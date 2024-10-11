<?php

namespace App\View\Helper;

use App\Models\Helper\Enum\Trait\ConvertsToArray;

enum DisplayModeType: int
{
    use ConvertsToArray;

    case TEXT = 0;
    case BADGE = 1;

    public static function get($type = null): self
    {
        if (empty($type)) {
            return self::TEXT;
        }
        if ($type instanceof self) {
            return $type;
        }
        return self::from((int) $type);
    }
}
