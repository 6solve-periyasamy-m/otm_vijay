<?php

namespace App\View\Helper;

use App\Models\Helper\Enum\Trait\ConvertsToArray;

enum DisplayModeColor: string
{
    use ConvertsToArray;

    case DARK = 'dark';
    case GRAY = 'secondary';
    case WHITE = 'white';
    case RED = 'danger';
    case YELLOW = 'warning';
    case GREEN = 'success';
    case TEAL = 'info';
    case BLUE = 'primary';

    public static function get(self|string|null $color): self
    {
        if ($color instanceof self) { return $color; }
        if (empty($color)) {
            return self::DARK;
        }
        if (is_string($color)) {
            $color = self::from($color);
        }
        return $color;
    }
}
