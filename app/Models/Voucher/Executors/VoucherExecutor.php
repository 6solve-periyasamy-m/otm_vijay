<?php

namespace App\Models\Voucher\Executors;

use App\Models\Order\OrderCustomer;
use App\Models\Voucher\ResultType;
use App\Models\Voucher\VoucherCode;
use App\Models\Voucher\VoucherCodeResult;

abstract class VoucherExecutor
{
    protected VoucherCodeResult $result;
    protected VoucherCode|null $voucher;

    public function __construct(VoucherCodeResult $result)
    {
        $this->result = $result;
        $this->voucher = $result->voucher;
    }

    public static function getExample($type, $data): string
    {
        return match (ResultType::from($type)) {
            ResultType::FREE_COMPONENT => FlatCostReductionExecutor::fromJson($data)->description(),
            ResultType::FLAT_ADJUSTMENT => FlatCostReductionExecutor::fromJson($data)->description(),
            ResultType::PERCENTAGE_ADJUSTMENT => PercentageCostReductionExecutor::fromJson($data)->description(),
        };
    }

    public abstract static function fromJson(array $data): VoucherExecutor;

    public abstract function description(): string;

    public abstract function applyForOrderCustomer(OrderCustomer $orderCustomer);
}
