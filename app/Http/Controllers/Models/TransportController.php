<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TransportController extends Controller
{

    public function index()
    {
        return view('pages.models.transports.table', ['transports' => Transport::all(),]);
    }

    public function create()
    {
        return view('pages.models.transports.create');
    }

    public function store(Request $request)
    {
        $request->validate(Transport::getValidationRules());
        $transport = Transport::create([
            'transport_type_id' => $request->input('transport_type_id'),
            'operator_id' => $request->input('operator_id'),
            'departure_address_id' => $request->input('departure_address_id'),
            'arrival_address_id' => $request->input('arrival_address_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'currency_id' => $request->input('currency_id'),
            'is_domestic' => $request->input('is_domestic') === 'on' ? 1 : 0,
            'notes' => $request->input('notes'),
        ]);
        if ($request->has('image') && $request->file('image') != null) {
            $transport->image_url = $request->file('image')->storePublicly('uploads/images');
        }
        return redirect()->route('transports.view', ['transport' => $transport,]);
    }

    public function view(Transport $transport)
    {
        return view('pages.components.transport', ['transport' => $transport,]);
    }

    public function edit(Transport $transport)
    {
        return view('pages.models.transports.update', ['transport' => $transport,]);
    }

    public function update(Request $request, Transport $transport)
    {
        $request->validate(Transport::getValidationRules());
        $transport->update([
            'transport_type_id' => $request->input('transport_type_id'),
            'operator_id' => $request->input('operator_id'),
            'departure_address_id' => $request->input('departure_address_id'),
            'arrival_address_id' => $request->input('arrival_address_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'currency_id' => $request->input('currency_id'),
            'is_domestic' => $request->input('is_domestic') == "on" ? 1 : 0,
            'notes' => $request->input('notes'),
        ]);
        if ($request->has('image') && $request->file('image') != null) {
            if (isset($transport->image_url)) {
                File::delete(public_path($transport->image_url));
            }
            $transport->image_url = $request->file('image')->storePublicly('uploads/images');
        }
        return redirect()->route('transports.view', ['transport' => $transport,]);
    }

    public function destroy(Transport $transport)
    {
        foreach ($transport->transportInventory as $inventory) {
            if ($inventory->tourComponents()->count() > 0) {
                return back()->withErrors(trans('custom.used-in-tour', ['model' => 'Transport']));
            }
        }
        $transport->delete();
        return redirect()->route('transports.all');
    }

    public function createReturn(Transport $transport)
    {
        $return = $transport->replicate();
        $depart = $transport->arrival_address_id;
        $arrival = $transport->departure_address_id;
        $return->departure_address_id = $depart;
        $return->arrival_address_id = $arrival;
        $return->save();
        return redirect()->route('transports.edit', ['transport' => $return,]);
    }
}
