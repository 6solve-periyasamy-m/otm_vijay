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
        $percent = $this->calculate($orderCustomer->order->repository->getTravellerBaseCosts());
        $orderCustomer->order->repository->addAdjustment(
            $percent,
            "Voucher Code: {$this->voucher->code} - {$this->amount}% off",
            now(),
        );
    }

    private function calculate(int|float $cost): int|float
    {
        return sigfig($cost * ($this->amount / 100)) * -1;
    }

    public static function fromJson(array $data): VoucherExecutor
    {
        return new static(new VoucherCodeResult(['result_type' => ResultType::PERCENTAGE_ADJUSTMENT, 'data' => $data,]));
    }

    public function description(): string
    {
        return __('voucher.result.type.percentage_reduction.description',[
            'total' => f_currency(1000),
            'reduction' => $this->amount . '%',
            'after' => f_currency(1000 - $this->calculate(1000)),
        ]);
    }
}
