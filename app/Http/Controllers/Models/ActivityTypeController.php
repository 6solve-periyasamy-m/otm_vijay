<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\ActivityType;
use Illuminate\Http\Request;

class ActivityTypeController extends Controller
{

    public function index()
    {
        return view('pages.models.activity_types.table', ['activityTypes' => ActivityType::all(),]);
    }

    public function create()
    {
        return view('pages.models.activity_types.create');
    }

    public function store(Request $request)
    {
        $request->validate(ActivityType::getValidationRules());
        $activityType = ActivityType::create([
            'name' => $request->input('name'),
        ]);
        return redirect()->route('activity-types.view', ['activityType' => $activityType,]);
    }

    public function view(ActivityType $activityType)
    {
        return view('pages.models.activity_types.view', ['activityType' => $activityType,]);
    }

    public function edit(ActivityType $activityType)
    {
        return view('pages.models.activity_types.update', ['activityType' => $activityType,]);
    }

    public function update(Request $request, ActivityType $activityType)
    {
        $request->validate(ActivityType::getValidationRules());
        $activityType->update([
            'name' => $request->input('name'),
        ]);
        return redirect()->route('activity-types.view', ['activityType' => $activityType,]);
    }

    public function destroy(ActivityType $activityType)
    {
        $activityType->delete();
        return redirect()->route('activity-types.all');
    }
}
