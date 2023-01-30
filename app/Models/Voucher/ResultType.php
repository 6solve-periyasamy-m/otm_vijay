<?php

namespace App\Models\Voucher;

enum ResultType: int
{
    case FLAT_ADJUSTMENT = 0;
    case PERCENTAGE_ADJUSTMENT = 1;

}
