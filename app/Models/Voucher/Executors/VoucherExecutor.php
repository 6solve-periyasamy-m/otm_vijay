<?php

namespace App\Models\Voucher\Executors;

use App\Models\Order\OrderCustomer;
use App\Models\Voucher\VoucherCode;
use App\Models\Voucher\VoucherCodeResult;

abstract class VoucherExecutor
{
    protected VoucherCodeResult $result;
    protected VoucherCode $voucher;

    public function __construct(VoucherCodeResult $result)
    {
        $this->result = $result;
        $this->voucher = $result->voucher;
    }

    public abstract function applyForOrderCustomer(OrderCustomer $orderCustomer);
}
