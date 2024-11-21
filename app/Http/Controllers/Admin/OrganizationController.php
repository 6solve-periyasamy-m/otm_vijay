<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrganizationRequest;
use App\Models\Customer\Organization;

class OrganizationController extends Controller
{
    public function index()
    {
        return view('pages.admin.organization.table');
    }

    public function create()
    {
        return view('pages.admin.organization.form');
    }

    public function store(OrganizationRequest $request)
    {
        $organization = Organization::create($request->getData());
        return redirect()->route('organizations.view', ['organization' => $organization,]);
    }

    public function view(Organization $organization)
    {
        $organization->load('customers', 'memberOrders', 'memberQuotes', 'quotes', 'orders', 'agents');
        return view('pages.admin.organization.view', ['organization' => $organization,]);
    }

    public function edit(Organization $organization)
    {
        return view('pages.admin.organization.form', ['organization' => $organization,]);
    }

    public function update(OrganizationRequest $request, Organization $organization)
    {
        $organization->update($request->getData());
        $organization->save();
        return redirect()->route('organizations.view', ['organization' => $organization,]);
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();
        return redirect()->route('organizations.all');
    }
}
