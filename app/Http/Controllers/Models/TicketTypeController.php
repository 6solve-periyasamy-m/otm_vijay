<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{

    public function index()
    {
        return view('pages.models.ticket_types.table', ['ticketTypes' => TicketType::all(),]);
    }

    public function create()
    {
        return view('pages.models.ticket_types.create');
    }

    public function store(Request $request)
    {
        $request->validate(TicketType::getValidationRules());
        $ticketType = TicketType::create([
            'name' => $request->input('name'),
        ]);
        return redirect()->route('ticket-types.view', ['ticketType' => $ticketType,]);
    }

    public function view(TicketType $ticketType)
    {
        return view('pages.models.ticket_types.view', ['ticketType' => $ticketType,]);
    }

    public function edit(TicketType $ticketType)
    {
        return view('pages.models.ticket_types.update', ['ticketType' => $ticketType,]);
    }

    public function update(Request $request, TicketType $ticketType)
    {
        $request->validate(TicketType::getValidationRules());
        $ticketType->update([
            'name' => $request->input('name'),
        ]);
        return redirect()->route('ticket-types.view', ['ticketType' => $ticketType,]);
    }

    public function destroy(TicketType $ticketType)
    {
        $ticketType->delete();
        return redirect()->route('ticket-types.all');
    }
}
