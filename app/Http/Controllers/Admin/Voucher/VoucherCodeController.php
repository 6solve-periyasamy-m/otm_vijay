<?php

namespace App\Http\Controllers\Admin\Voucher;

use App\Http\Controllers\Controller;

class VoucherCodeController extends Controller
{
    public function index()
    {
        return view('pages.admin.voucher.table');
    }
}
