<?php

namespace App\Http\Controllers;

class ECommDashboardController extends Controller
{
    /**
     * Display the dashboard with the latest booking order.
     */

    public function dashboard()
    {
        return view('pages.ecommdash');
    }
}
