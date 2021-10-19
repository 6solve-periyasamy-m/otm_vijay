<?php

namespace App\Transforms;

use App\Models\Quote;

interface OrderTransformsInterface
{
    public static function getSelectQuotes($filter);

    public static function getSelectedQuote($id);
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
}
