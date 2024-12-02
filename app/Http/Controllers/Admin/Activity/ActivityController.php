<?php

namespace App\Http\Controllers\Admin\Activity;

use App\Http\Controllers\Controller;
use App\Models\Activity\Activity;
use App\Models\Helper\Enum\AddressParent;
use App\Models\Location\Address;
use App\Repository\Model\Location\AddressRepository;
use App\Repository\Reporting\Manifest\ActivityManifestRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ActivityController extends Controller
{

    public function index()
    {
        return view('pages.admin.activity.table', ['activities' => Activity::all(),]);
    }

    public function create()
    {
        return view('pages.admin.activity.form');
    }

    public function store(Request $request)
    {
        $request->validate(Activity::getValidationRules());
        $activity = Activity::make([
            'activity_type_id' => $request->input('activity_type_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'currency_id' => $request->input('currency_id'),
            'internal_notes' => $request->input('notes'),
            'activity_category' => $request->input('activity_category'),
            'event_id' => $request->input('event_id'),
        ]);
        if ($request->input('use_existing') == 'on') {
            $address = Address::findOrFail($request->input('address_id'))->repository->cloneToNew(AddressParent::ACTIVITY);
        } else {
            $request->validate(Address::getValidationRules());
            $address = new Address(AddressRepository::getArrayFromGenericRequest($request, $request->input('address_name'), AddressParent::ACTIVITY));
            $address->repository->save();
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
        return view('pages.admin.activity.view', ['activity' => $activity,]);
    }

    public function manifest(Activity $activity)
    {
        return ActivityManifestRepository::viewReport($activity->repository, 'activities.manifest.export', ['activity' => $activity,]);
    }

    public function export(Activity $activity, string $extension = 'xlsx')
    {
        return ActivityManifestRepository::exportReport($activity->repository, $extension);
    }

    public function edit(Activity $activity)
    {
        return view('pages.admin.activity.form', ['activity' => $activity,]);
    }

    public function update(Request $request, Activity $activity)
    {
        $request->validate(Activity::getValidationRules());
        $activity->update([
            'activity_type_id' => $request->input('activity_type_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'currency_id' => $request->input('currency_id'),
            'internal_notes' => $request->input('notes'),
            'activity_category' => $request->input('activity_category'),
            'event_id' => $request->input('event_id'),
        ]);
        if ($request->input('use_existing') == 'on') {
            Address::findOrFail($request->input('address_id'))->repository->cloneToNew(AddressParent::ACTIVITY, $activity->address);
        } else {
            $request->validate(Address::getValidationRules());
            $activity->address->update(AddressRepository::getArrayFromGenericRequest($request, $request->input('address_name'), AddressParent::ACTIVITY));
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
}
