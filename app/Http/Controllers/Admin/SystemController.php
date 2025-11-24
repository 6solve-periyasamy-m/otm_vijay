<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SystemController extends Controller
{
    public function administration()
    {
        return view('pages.admin.system.admin');
    }
}