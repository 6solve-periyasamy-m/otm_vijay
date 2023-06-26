<?php

namespace App\Http\Controllers\Admin\Voucher;

use App\Http\Controllers\Controller;
use App\Models\Voucher\VoucherCode;

class VoucherCodeController extends Controller
{
    public function index()
    {
        return view('pages.admin.voucher.table');
    }

    public function view(VoucherCode $voucher)
    {
        return view('pages.admin.voucher.view', ['voucher' => $voucher,]);
    }
}
