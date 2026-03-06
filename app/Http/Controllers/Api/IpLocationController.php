<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use EventLogger;

class IpLocationController extends Controller
{
    public function getLocation(Request $request)
    {
        $ip = $request->input('ip');
        if (!$ip) {
            return response()->json(['error' => 'IP address required'], 400);
        }
        $location = EventLogger::getLocationByIp($ip);
        if (!$location) {
            return response()->json(['error' => 'Location not found'], 404);
        }
        return response()->json($location);
    }
}
