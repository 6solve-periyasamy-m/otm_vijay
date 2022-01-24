<?php

namespace App\Http\Controllers;

use App\Events\Order\Customer\Component\OrderCustomerComponentRemovedEvent;
use App\Models\OrderAccommodation;
use App\Models\OrderActivity;
use App\Models\OrderFlight;
use App\Models\OrderMerchandise;
use App\Models\OrderTransport;
use Illuminate\Http\Request;

class OrderComponentController extends Controller
{
    public function deleteAccommodation(Request $request, $id) {
        $orderComponent = OrderAccommodation::findOrFail($id);
        $orderComponent->delete();
        event(new OrderCustomerComponentRemovedEvent($orderComponent));
        return redirect($request->has('redirect') ? $request->input('redirect') : route('/'));
    }

    public function deleteActivity(Request $request, $id) {
        $orderComponent = OrderActivity::findOrFail($id);
        $orderComponent->delete();
        event(new OrderCustomerComponentRemovedEvent($orderComponent));
        return redirect($request->has('redirect') ? $request->input('redirect') : route('/'));
    }

    public function deleteFlight(Request $request, $id) {
        $orderComponent = OrderFlight::findOrFail($id);
        $orderComponent->delete();
        event(new OrderCustomerComponentRemovedEvent($orderComponent));
        return redirect($request->has('redirect') ? $request->input('redirect') : route('/'));
    }

    public function deleteTransport(Request $request, $id) {
        $orderComponent = OrderTransport::findOrFail($id);
        $orderComponent->delete();
        event(new OrderCustomerComponentRemovedEvent($orderComponent));
        return redirect($request->has('redirect') ? $request->input('redirect') : route('/'));
    }

    public function deleteMerchandise(Request $request, $id) {
        $orderComponent = OrderMerchandise::findOrFail($id);
        $orderComponent->delete();
        event(new OrderCustomerComponentRemovedEvent($orderComponent));
        return redirect($request->has('redirect') ? $request->input('redirect') : route('/'));
    }

}
