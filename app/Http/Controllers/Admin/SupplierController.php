<?php

namespace App\Http\Controllers\Admin;

use App\Models\Supplier\Supplier;
use App\Models\Supplier\SupplierContract;
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

    public function contract(Supplier $supplier, SupplierContract $contract)
    {
        return view('pages.admin.supplier.contract.view', ['contract' => $contract,]);
    }

    public function link(Supplier $supplier, SupplierContract $contract)
    {
        return view('pages.admin.supplier.contract.link', ['contract' => $contract,]);
    }
}
