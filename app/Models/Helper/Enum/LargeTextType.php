<?php

namespace App\Models\Helper\Enum;

use App\Models\Helper\Enum\Trait\ConvertsToArray;

enum LargeTextType: int
{
    use ConvertsToArray;

    case GENERIC = 0;
    case TERMS = 1;
    case INVOICE_FOOTER = 2;

    public function label()
    {
        return match ($this) {
            self::GENERIC => __('custom.text.type.generic'),
            self::TERMS => __('custom.text.type.terms'),
            self::INVOICE_FOOTER => __('custom.text.type.invoice_footer'),
        };
    }
}
