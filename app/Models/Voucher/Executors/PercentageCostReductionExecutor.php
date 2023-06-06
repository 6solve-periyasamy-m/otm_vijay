<?php

namespace App\Models\Voucher\Executors;

use App\Models\Order\OrderCustomer;
use App\Models\Voucher\ResultType;
use App\Models\Voucher\VoucherCodeResult;

class PercentageCostReductionExecutor extends VoucherExecutor
{
    private float $amount;

    public function __construct(VoucherCodeResult $result)
    {
        parent::__construct($result);
        $this->amount = sigfig($result->data['amount'] ?? 0);
    }

    public static function create(float $amount): VoucherCodeResult
    {
        return VoucherCodeResult::make([
            'result_type' => ResultType::PERCENTAGE_ADJUSTMENT,
            'data' => ['amount' => sigfig($amount),]
        ]);
    }

    public function applyForOrderCustomer(OrderCustomer $orderCustomer)
    {
        $percent = sigfig($orderCustomer->order->repository->getTravellerBaseCosts() * ($this->amount / 100)) * -1;
        $orderCustomer->order->repository->addAdjustment(
            $percent,
            "Voucher Code: {$this->voucher->code} - {$this->amount}% off",
            now(),
        );
    }
}
