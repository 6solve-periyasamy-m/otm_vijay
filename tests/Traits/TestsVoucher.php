<?php

namespace Tests\Traits;

use App\Models\Voucher\VoucherCode;

trait TestsVoucher
{
    public function generateVoucher(bool $global = true): VoucherCode
    {
        return VoucherCode::factory()->create(['global' => $global]);
    }
}
