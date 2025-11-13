<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\LoyaltyNumberType;

class LoyaltyNumberTypeController extends Controller
{
    public function create()
    {
        return view('pages.admin.customer.loyalty-number-type.form');
    }

    public function edit(LoyaltyNumberType $type)
    {
        return view('pages.admin.customer.loyalty-number-type.form', ['type' => $type,]);
    }

    public function delete(LoyaltyNumberType $type)
    {
        if ($type->repository->getRelatedCount() > 0) {
            return back()->withErrors(['msg' => 'Cannot delete Loyalty Number Type with related models']);
        }
        $type->delete();
        return back()->with(['success' => 'Loyalty Number Type has been deleted']);
    }
}