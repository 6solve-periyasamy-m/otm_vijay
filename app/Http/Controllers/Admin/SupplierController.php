<?php

namespace App\Http\Controllers\Admin;

use App\Models\Supplier\Supplier;
use Illuminate\Routing\Controller;

class SupplierController extends Controller
{
    public function index()
    {
        return view('pages.admin.supplier.table');
    }

    public function view(Supplier $supplier)
    {
        return view('pages.admin.supplier.view', ['supplier' => $supplier,]);
    }
}
