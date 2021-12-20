<?php

namespace App\Http\Controllers;

use App\Models\OrderAccommodation;
use App\Models\OrderActivity;
use App\Models\OrderFlight;
use App\Models\OrderMerchandise;
use App\Models\OrderTransport;
use Illuminate\Http\Request;

class OrderComponentController extends Controller
{
    public function deleteAccommodation(Request $request, $id) {
        OrderAccommodation::findOrFail($id)->delete();
        return redirect($request->has('redirect') ? $request->input('redirect') : route('/'));
    }

    public function deleteActivity(Request $request, $id) {
        OrderActivity::findOrFail($id)->delete();
        return redirect($request->has('redirect') ? $request->input('redirect') : route('/'));
    }

    public function deleteFlight(Request $request, $id) {
        OrderFlight::findOrFail($id)->delete();
        return redirect($request->has('redirect') ? $request->input('redirect') : route('/'));
    }

    public function deleteTransport(Request $request, $id) {
        OrderTransport::findOrFail($id)->delete();
        return redirect($request->has('redirect') ? $request->input('redirect') : route('/'));
    }

    public function deleteMerchandise(Request $request, $id) {
        OrderMerchandise::findOrFail($id)->delete();
        return redirect($request->has('redirect') ? $request->input('redirect') : route('/'));
    }

}
