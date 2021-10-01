<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Activity;
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
        $activity = Activity::create([
            'activity_type_id' => $request->input('activity_type_id'),
            'location_id' => $request->input('location_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'notes' => $request->input('notes'),
        ]);
        return redirect()->route('activities.view', ['activity' => $activity,]);
    }

    public function view(Activity $activity)
    {
        return view('pages.models.activities.view', ['activity' => $activity,]);
    }

    public function edit(Activity $activity)
    {
        return view('pages.models.activities.update', ['activity' => $activity,]);
    }

    public function update(Request $request, Activity $activity)
    {
        $activity->update([
            'activity_type_id' => $request->input('activity_type_id'),
            'location_id' => $request->input('location_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'notes' => $request->input('notes'),
        ]);
        return redirect()->route('activities.view', ['activity' => $activity,]);
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->route('activities.all');
    }
}
