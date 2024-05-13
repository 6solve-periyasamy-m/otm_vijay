<?php

namespace App\Http\Controllers\Admin\Activity;

use App\Http\Controllers\Controller;
use App\Models\Activity\ActivityType;
use Illuminate\Http\Request;

class ActivityTypeController extends Controller
{
    public function create()
    {
        return view('pages.admin.activity.type.form');
    }

    public function store(Request $request)
    {
        $request->validate(ActivityType::getValidationRules());
        $activityType = ActivityType::create([
            'name' => $request->input('name'),
        ]);
        return view('pages.close');
    }

    public function edit(ActivityType $activityType)
    {
        return view('pages.admin.activity.type.form', ['activityType' => $activityType,]);
    }

    public function update(Request $request, ActivityType $activityType)
    {
        $request->validate(ActivityType::getValidationRules($activityType->id));
        $activityType->update([
            'name' => $request->input('name'),
        ]);
        return view('pages.close');
    }

    public function destroy(ActivityType $activityType)
    {
        $repo = $activityType->repository;
        $repo->delete();
        return $repo->getReturnURL();
    }
}
