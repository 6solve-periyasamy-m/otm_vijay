<?php

namespace App\Models\Voucher;

enum ResultType: int
{
    case FLAT_ADJUSTMENT = 0;
    case PERCENTAGE_ADJUSTMENT = 1;
    case FREE_COMPONENT = 2;

    public function description(): string
    {
        return match ($this) {
            self::FLAT_ADJUSTMENT => __('voucher.result.type.flat_reduction.name'),
            self::PERCENTAGE_ADJUSTMENT => __('voucher.result.type.percentage_reduction.name'),
            self::FREE_COMPONENT => __('voucher.result.type.free_component.name'),
        };
    }
}
