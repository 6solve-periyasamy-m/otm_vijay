<?php

namespace App\Http\Controllers;

use App\Models\OrdersAccommodation;
use App\Models\OrdersActivity;
use App\Models\OrdersFlight;
use App\Models\OrdersTransport;
use Illuminate\Http\Request;

class OrderComponentController extends Controller
{
    public function deleteAccommodation(Request $request, $id) {
        OrdersAccommodation::findOrFail($id)->delete();
        return redirect($request->has('redirect') ? $request->input('redirect') : route('/'));
    }

    public function deleteActivity(Request $request, $id) {
        OrdersActivity::findOrFail($id)->delete();
        return redirect($request->has('redirect') ? $request->input('redirect') : route('/'));
    }

    public function deleteFlight(Request $request, $id) {
        OrdersFlight::findOrFail($id)->delete();
        return redirect($request->has('redirect') ? $request->input('redirect') : route('/'));
    }

    public function deleteTransport(Request $request, $id) {
        OrdersTransport::findOrFail($id)->delete();
        return redirect($request->has('redirect') ? $request->input('redirect') : route('/'));
    }

}
