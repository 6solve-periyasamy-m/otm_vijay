<?php

namespace App\Transforms;

use App\Models\Customer;
use App\Models\OrderCustomer;
use App\Models\PaymentMethod;
use App\Models\Quote;
use Illuminate\Support\Facades\Log;

interface OrderTransformsInterface
{
    public static function getSelectQuotes($filter);

    public static function getSelectedQuote($id);

    public static function getSelectCustomers($filter);

    public static function getSelectedCustomer($id);

    public static function getSelectPaymentMethods($filter);

    public static function getSelectedPaymentMethod($id);

    public static function getAvailableMerchandise(OrderCustomer $orderCustomer, $filter);
}

class OrderTransforms implements OrderTransformsInterface
{

    public static function getSelectQuotes($filter)
    {
        $data = [];
        foreach (Quote::all() as $quote) {
            $subData = [];
            $subData['id'] = $quote->id;
            $subData['text'] = $quote->pax_number . ' - ' . $quote->customer->first_name . ' ' . $quote->customer->last_name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }


    public static function getSelectedQuote($id)
    {
        if ($id == 0) return null;
        $quote = Quote::findOrFail($id);
        $data = [];
        $data['id'] = $quote->id;
        $data['text'] = $quote->pax_number . ' - ' . $quote->customer->first_name . ' ' . $quote->customer->last_name;
        return $data;
    }

    public static function getSelectCustomers($filter)
    {
        $data = [];
        foreach (Customer::all() as $customer) {
            $subData = [];
            $subData['id'] = $customer->id;
            $subData['text'] = $customer->first_name . ' ' . $customer->last_name . ' - ' . $customer->email_address;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }


    public static function getSelectedCustomer($id)
    {
        if ($id == 0) return null;
        $customer = Customer::findOrFail($id);
        $data = [];
        $data['id'] = $customer->id;
        $data['text'] = $customer->first_name . ' ' . $customer->last_name . ' - ' . $customer->email_address;
        return $data;
    }

    public static function getSelectPaymentMethods($filter)
    {
        $data = [];
        foreach (PaymentMethod::all() as $method) {
            $subData = [];
            $subData['id'] = $method->id;
            $subData['text'] = $method->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }


    public static function getSelectedPaymentMethod($id)
    {
        if ($id == 0) return null;
        $method = PaymentMethod::findOrFail($id);
        $data = [];
        $data['id'] = $method->id;
        $data['text'] = $method->name;
        return $data;
    }

    public static function getAvailableMerchandise(OrderCustomer $orderCustomer, $filter)
    {
        $tour = $orderCustomer->order->tour;
        $data = [];
        $owned = [];
        foreach ($orderCustomer->orderMerchandise as $orderMerch) $owned[] = $orderMerch->merchandise->id;
        foreach ($tour->merchandise as $merch) {
            if ($merch->tour_component_type === "Add-on") {
                if (in_array($merch->id, $owned)) continue;
                $subData = [];
                $subData['id'] = $merch->id;
                $subData['text'] = $merch->name . ' - ' . \App\Facades\StringFormatterFacade::formatCurrency($merch->tour_sales_price);
                if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
            }
        }
        return $data;
    }
}
