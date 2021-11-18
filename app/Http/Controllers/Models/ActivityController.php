<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Address;
use App\Models\AddressParent;
use App\Repository\LocationsRepository;
use Illuminate\Http\Request;

class ActivityController extends Controller
{

    public function index()
    {
        return view('pages.models.activities.table', ['activities' => Activity::all(),]);
    }

    public function create()
    {
        return view('pages.models.activities.create');
    }

    public function store(Request $request)
    {
        $request->validate(Activity::RULES);
        $activity = Activity::make([
            'activity_type_id' => $request->input('activity_type_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'notes' => $request->input('notes'),
        ]);
        if ($request->input('use_existing') == 'on') {
            $address = LocationsRepository::cloneAddressToAddress(Address::findOrFail($request->input('address_id')));
        } else {
            $address = LocationsRepository::storeAddressFromGenericRequest(null, AddressParent::getParentId('activity'), $request, $request->input('name'), '');
        }
        $activity->address_id = $address->id;
        $activity->save();
        return redirect()->route('activities.view', ['activity' => $activity,]);
    }

    public function view(Activity $activity)
    {
        return view('pages.components.activity', ['activity' => $activity,]);
    }

    public function edit(Activity $activity)
    {
        return view('pages.models.activities.update', ['activity' => $activity,]);
    }

    public function update(Request $request, Activity $activity)
    {
        $request->validate(Activity::RULES);
        $activity->update([
            'activity_type_id' => $request->input('activity_type_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'notes' => $request->input('notes'),
        ]);
        if ($request->input('use_existing') == 'on') {
            LocationsRepository::cloneAddressToAddress(Address::findOrFail($request->input('address_id')), $activity->address);
        } else {
            LocationsRepository::storeAddressFromGenericRequest($activity->address, AddressParent::getParentId('activity'), $request, $request->input('name'), '');
        }
        $activity->save();
        return redirect()->route('activities.view', ['activity' => $activity,]);
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->route('activities.all');
    }
}
