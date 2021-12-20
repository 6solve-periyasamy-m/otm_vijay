<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Address;
use App\Models\AddressParent;
use App\Repository\LocationsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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
        $request->validate(Activity::getValidationRules());
        $activity = Activity::make([
            'activity_type_id' => $request->input('activity_type_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'currency_id' => $request->input('currency_id'),
            'notes' => $request->input('notes'),
        ]);
        if ($request->input('use_existing') == 'on') {
            $address = LocationsRepository::cloneAddressToAddress(Address::findOrFail($request->input('address_id')), AddressParent::getParentId('activity'));
        } else {
            $request->validate(Address::getValidationRules());
            $address = LocationsRepository::storeAddressFromGenericRequest(null, AddressParent::getParentId('activity'), $request, $request->input('name'), '');
        }
        if ($request->has('image') && $request->file('image') != null) {
            $activity->image_url = $request->file('image')->storePublicly('uploads/images');
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
        $request->validate(Activity::getValidationRules());
        $activity->update([
            'activity_type_id' => $request->input('activity_type_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'currency_id' => $request->input('currency_id'),
            'notes' => $request->input('notes'),
        ]);
        if ($request->input('use_existing') == 'on') {
            LocationsRepository::cloneAddressToAddress(Address::findOrFail($request->input('address_id')), AddressParent::getParentId('activity'), $activity->address);
        } else {
            $request->validate(Address::getValidationRules());
            LocationsRepository::storeAddressFromGenericRequest($activity->address, AddressParent::getParentId('activity'), $request, $request->input('name'), '');
        }
        if ($request->has('image') && $request->file('image') != null) {
            if (isset($activity->image_url)) {
                File::delete(public_path($activity->image_url));
            }
            $activity->image_url = $request->file('image')->storePublicly('uploads/images');
        }
        $activity->save();
        return redirect()->route('activities.view', ['activity' => $activity,]);
    }

    public function destroy(Activity $activity)
    {
        foreach ($activity->activityInventory as $inventory) {
            if ($inventory->tourComponents()->count() > 0) {
                return back()->withErrors(trans('custom.used-in-tour', ['model' => 'Activity']));
            }
        }
        $activity->delete();
        return redirect()->route('activities.all');
    }
}
