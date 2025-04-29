<?php

namespace App\Models\Helper\Enum;

use App\Models\Helper\Enum\Trait\ConvertsToArray;

enum LargeTextType: int
{
    use ConvertsToArray;

    case GENERIC = 0;
    case TERMS = 1;
    case INVOICE_FOOTER = 2;
    case QUOTE_TEMPLATE = 3;
    case PAYMENT_DETAILS = 4;

    public function label()
    {
        return match ($this) {
            self::GENERIC => __('custom.text.type.generic'),
            self::TERMS => __('custom.text.type.terms'),
            self::INVOICE_FOOTER => __('custom.text.type.invoice_footer'),
            self::QUOTE_TEMPLATE => __('custom.text.type.quote_template'),
            self::PAYMENT_DETAILS => __('custom.text.type.payment_details'),
        };
    }
}
