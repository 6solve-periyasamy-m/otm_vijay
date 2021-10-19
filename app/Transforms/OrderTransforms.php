<?php

namespace App\Transforms;

use App\Models\Customer;
use App\Models\Quote;

interface OrderTransformsInterface
{
    public static function getSelectQuotes($filter);

    public static function getSelectedQuote($id);

    public static function getSelectCustomers($filter);

    public static function getSelectedCustomer($id);
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
        $quote = Customer::findOrFail($id);
        $data = [];
        $data['id'] = $quote->id;
        $data['text'] = $quote->pax_number . ' - ' . $quote->customer->first_name . ' ' . $quote->customer->last_name;
        return $data;
    }
}
