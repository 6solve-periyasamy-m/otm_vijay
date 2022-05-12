<?php

namespace App\Repository\Model;

use App\Models\Order\Order;

class OrderRepository
{
    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public static function getFromBookingReference(string $reference): ?Order
    {
        return Order::whereBookingReference($reference)->first();
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getInvoiceRepository(int $number = 0): InvoiceRepository
    {
        return InvoiceRepository::getInvoiceById($this->order, $number);
    }
}
