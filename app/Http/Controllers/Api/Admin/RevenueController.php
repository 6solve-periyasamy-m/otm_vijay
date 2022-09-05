<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\RevenueHelper;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\Admin\Costing\RevenueBetweenDatesRequest;

class RevenueController extends ApiController
{
    public function revenue(RevenueBetweenDatesRequest $request)
    {
        $from = $request->getFromDate();
        $to = $request->getToDate();
        if (!(isset($from) && isset($to))) return response()->json(['success' => false, 'message' => 'Failed to parse dates']);
        $data = RevenueHelper::getExpectedRevenue($from, $to);
        return response()->json(['success' => true, ...$data]);
    }
}
