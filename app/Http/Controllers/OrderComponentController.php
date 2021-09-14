<?php

namespace App\Http\Controllers;

use App\Models\OrdersAccommodation;
use Illuminate\Http\Request;

class OrderComponentController extends Controller
{
    public function deleteAccommodation(Request $request, $id) {
        OrdersAccommodation::findOrFail($id)->delete();
        return redirect($request->has('redirect') ? $request->input('redirect') : route('/'));
    }

}
