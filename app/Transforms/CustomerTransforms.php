<?php

namespace App\Transforms;

use App\Models\Customer\HatSize;
use App\Models\Customer\Organization;
use App\Models\Customer\Agent;
use App\Models\Customer\TShirtSize;
use App\Models\Customer\AirlineFrequentFlyers;


interface CustomerTransformsInterface
{
    public static function getSelectTShirtSizes($filter);

    public static function getSelectedTShirtSize($id);

    public static function getSelectHatSizes($filter);

    public static function getSelectedHatSize($id);

    public static function getSelectedFrequentFlyer($id);
}

class CustomerTransforms implements CustomerTransformsInterface
{

    public static function getSelectTShirtSizes($filter)
    {
        $data = [];
        foreach (TShirtSize::all() as $size) {
            $subData = [];
            $subData['id'] = $size->id;
            $subData['text'] = $size->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }


    public static function getSelectedTShirtSize($id)
    {
        if ($id == 0) return null;
        $size = TShirtSize::findOrFail($id);
        $data = [];
        $data['id'] = $size->id;
        $data['text'] = $size->name;
        return $data;
    }

    public static function getSelectHatSizes($filter)
    {
        $data = [];
        foreach (HatSize::all() as $size) {
            $subData = [];
            $subData['id'] = $size->id;
            $subData['text'] = $size->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }


    public static function getSelectedHatSize($id)
    {
        if ($id == 0) return null;
        $size = HatSize::findOrFail($id);
        $data = [];
        $data['id'] = $size->id;
        $data['text'] = $size->name;
        return $data;
    }

    public static function getSelectOrganizations($filter): array
    {
        $data = [];
        foreach (Organization::all() as $organization) {
            $subData = [];
            $subData['id'] = $organization->id;
            $subData['text'] = $organization->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }


    public static function getSelectedOrganization($id)
    {
        if ($id == 0) return null;
        $organization = Organization::findOrFail($id);
        $data = [];
        $data['id'] = $organization->id;
        $data['text'] = $organization->name;
        return $data;
    }

    public static function getSelectAgencies($organization_id)
    {
        if ($organization_id == 0) return null;
        $agents = Agent::where('organization_id', $organization_id)->get();
        $data = [];
        foreach ($agents as $agent) {
            $option = [];
            $option['id'] = $agent->id;
            $option['text'] = $agent->first_name . ' ' . $agent->last_name;
            $data['results'][] = $option;
        }
        return $data;
    }

    public static function getSelectFrequentFlyer($filter)
    {
        $data = [];
        foreach (AirlineFrequentFlyers::all() as $program) {
            $subData = [];
            $subData['id'] = $program->id;
            $subData['text'] = $program->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedFrequentFlyer($id)
    {
        if ($id == 0) return null;
        $program = AirlineFrequentFlyers::findOrFail($id);
        $data = [];
        $data['id'] = $program->id;
        $data['text'] = $program->name;
        return $data;
    }
}
