<?php

namespace App\Models\Voucher\Executors;

use App\Models\Order\OrderCustomer;
use App\Models\Voucher\ResultType;
use App\Models\Voucher\VoucherCodeResult;

class FlatCostReductionExecutor extends VoucherExecutor
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
            'result_type' => ResultType::FLAT_ADJUSTMENT,
            'data' => ['amount' => sigfig($amount),]
        ]);
    }

    public function applyForOrderCustomer(OrderCustomer $orderCustomer)
    {
        $orderCustomer->order->repository->addAdjustment(
            $this->amount,
            "Voucher Code: {$this->voucher->code}",
            now(),
        );
    }

    public static function fromJson(array $data): VoucherExecutor
    {
        return new static(new VoucherCodeResult(['result_type' => ResultType::FLAT_ADJUSTMENT, 'data' => $data,]));
    }

    public function description(): string
    {
        return __('voucher.result.description.flat_reduction',[
            'total' => f_currency(1000),
            'reduction' => f_currency($this->amount),
            'after' => f_currency(1000 + $this->amount),
        ]);
    }
}
