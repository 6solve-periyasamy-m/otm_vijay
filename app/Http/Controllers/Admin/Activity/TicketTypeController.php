<?php

namespace App\Http\Controllers\Admin\Activity;

use App\Http\Controllers\Controller;
use App\Models\Activity\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    public function create()
    {
        return view('pages.admin.activity.inventory.ticket.form');
    }

    public function store(Request $request)
    {
        $request->validate(TicketType::getValidationRules());
        $ticketType = TicketType::create([
            'name' => $request->input('name'),
        ]);
        return view('pages.close');
    }

    public function edit(TicketType $ticketType)
    {
        return view('pages.admin.activity.inventory.ticket.form', ['ticketType' => $ticketType,]);
    }

    public function update(Request $request, TicketType $ticketType)
    {
        $request->validate(TicketType::getValidationRules($ticketType->id));
        $ticketType->update([
            'name' => $request->input('name'),
        ]);
        return view('pages.close');
    }

    public function destroy(TicketType $ticketType)
    {
        $repo = $ticketType->repository;
        $repo->delete();
        return $repo->getReturnURL();
    }
}
