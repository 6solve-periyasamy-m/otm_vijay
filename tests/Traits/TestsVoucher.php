<?php

namespace Tests\Traits;

use App\Models\Voucher\VoucherCode;

trait TestsVoucher
{
    public function generateVoucher(): VoucherCode
    {
        return VoucherCode::factory()->create();
    }
}
