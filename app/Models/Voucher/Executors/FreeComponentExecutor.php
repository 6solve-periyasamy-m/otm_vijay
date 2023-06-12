<?php

namespace App\Models\Voucher\Executors;

use App\Models\Order\OrderCustomer;
use App\Models\Voucher\ResultType;
use App\Models\Voucher\VoucherCodeResult;
use App\Repository\Abstracts\InventoryTourRepository;

class FreeComponentExecutor extends VoucherExecutor
{
    private InventoryTourRepository $component;

    public function __construct(VoucherCodeResult $result)
    {
        parent::__construct($result);
        $this->component = InventoryTourRepository::getComponent($result->data['component']['type'], $result->data['component']['id']);
        $this->single = $result->data['single'] ?? true;
    }

    /**
     * @param InventoryTourRepository $component
     * @return VoucherCodeResult
     */
    public static function create(InventoryTourRepository $component): VoucherCodeResult
    {
        return VoucherCodeResult::make([
            'result_type' => ResultType::FREE_COMPONENT,
            'data' => [
                'component' => [
                    'type' => $component->getComponentType(),
                    'id' => $component->get()->id,
                ],
            ]
        ]);
    }

    public function applyForOrderCustomer(OrderCustomer $orderCustomer)
    {
        $component = $this->component->getOrderComponent($orderCustomer);
        if ($component === null) {
            $this->component->grantToCustomer($orderCustomer);
            if ($this->component->getTourComponentType() !== 'Included') {
                $orderCustomer->order->repository->addAdjustment(-$this->component->getCostToCustomer(), "Voucher Code {$this->voucher->code}: Free Component for {$orderCustomer->customer_name}");
            }
        }
    }
}
