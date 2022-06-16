<?php

namespace App\Repository;

use App\Models\Customer\Customer;
use App\Models\Order\OrderCustomer;

class CustomerDashboardRepository
{
    public static function getEditableCustomers(Customer $customer): array
    {
        $ids = [];
        foreach ($customer->leadingOrders as $order) {
            foreach ($order->orderCustomers as $oOrderCustomer) {
                $found = $oOrderCustomer->customer;
                if (!isset($found->email_address) || !isset($found->password)) {
                    $ids[] = $found->id;
                }
            }
        }
        return self::hydrateCustomers(array_unique($ids));
    }

    private static function hydrateCustomers(array $ids): array
    {
        $data = [];
        foreach ($ids as $id) {
            $data[] = Customer::find($id);
        }
        return $data;
    }

    public static function canEditCustomer(Customer $editor, Customer $edited): bool
    {
        if (!isset($edited->email_address) || !isset($edited->password)) {
            foreach ($editor->leadingOrders as $order) {
                foreach ($order->orderCustomers as $oOrderCustomer) {
                    if ($oOrderCustomer->customer_id === $edited->id) return true;
                }
            }
        }
        return false;
    }
}
