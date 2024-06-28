<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function center()
    {
        return view('pages.admin.system.notifications');
    }
}
