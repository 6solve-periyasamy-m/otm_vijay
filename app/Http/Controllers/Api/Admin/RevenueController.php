<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\RevenueHelper;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Admin\Costing\RevenueBetweenDatesRequest;
use App\Http\Requests\Api\Admin\Costing\RevenueBetweenDatesSetRequest;

class RevenueController extends ApiController
{
    public function revenue(RevenueBetweenDatesRequest $request)
    {
        $from = $request->getFromDate();
        $to = $request->getToDate();
        if (!(isset($from) && isset($to))) return response()->json(['success' => false, 'message' => 'Failed to parse dates']);
        $data = RevenueHelper::getExpectedRevenue($from, $to);
        return response()->json(['success' => true, 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d'), ...$data]);
    }

    public function revenueSet(RevenueBetweenDatesSetRequest $request)
    {
        $data = [];
        foreach ($request->getRequestedDates() as $dates) {
            $from = $dates['from'];
            $to = $dates['to'];
            if (!(isset($from) && isset($to))) return response()->json(['success' => false, 'message' => 'Failed to parse dates']);
            $data[] = ['from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d'), ...RevenueHelper::getExpectedRevenue($from, $to)];
        }
        return response()->json(['success' => true, 'data' => $data,]);
    }
}
