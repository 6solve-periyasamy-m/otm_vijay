<?php

namespace App\Http\Controllers\Admin\Transport;

use App\Http\Controllers\Controller;
use App\Models\Transport\TransportType;
use Illuminate\Http\Request;

class TransportTypeController extends Controller
{

    public function index()
    {
        return view('pages.models.transport_types.table', ['transportTypes' => TransportType::all(),]);
    }

    public function create()
    {
        return view('pages.models.transport_types.create');
    }

    public function store(Request $request)
    {
        $request->validate(TransportType::getValidationRules());
        $transportType = TransportType::create([
            'name' => $request->input('name'),
        ]);
        return view('pages.close');
    }

    public function view(TransportType $transportType)
    {
        return view('pages.models.transport_types.view', ['transportType' => $transportType,]);
    }

    public function edit(TransportType $transportType)
    {
        return view('pages.models.transport_types.update', ['transportType' => $transportType,]);
    }

    public function update(Request $request, TransportType $transportType)
    {
        $request->validate(TransportType::getValidationRules($transportType->id));
        $transportType->update([
            'name' => $request->input('name'),
        ]);
        return view('pages.close');
    }

    public function destroy(TransportType $transportType)
    {
        $transportType->delete();
        return redirect()->route('transport-types.all');
    }
}
