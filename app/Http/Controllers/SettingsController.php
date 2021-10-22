<?php

namespace App\Http\Controllers;

use App\Repository\SettingsRepository;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    public static function getValidationRules() {
        return [
            'company_name' => 'required',
            'address_line_1' => 'required',
            'address_line_2' => 'required',
            'city' => 'required',
            'region' => 'required',
            'postcode' => 'required',
            'booking_prefix' => 'required',
            'atol_issuer' => 'required',
            'atol_number' => 'required',
        ];
    }

    public function edit() {
        return view('pages.settings.form');
    }

    public function update(Request $request) {
        $request->validate(SettingsController::getValidationRules());
        SettingsRepository::setAll([
            'company.name' => $request->input('company_name'),
            'company.address.line_1' => $request->input('address_line_1'),
            'company.address.line_2' => $request->input('address_line_2'),
            'company.address.city' => $request->input('city'),
            'company.address.region' => $request->input('region'),
            'company.address.postcode' => $request->input('postcode'),
            'booking.prefix' => $request->input('booking_prefix'),
            'atol.issuer' => $request->input('atol_issuer'),
            'atol.number' => $request->input('atol_number'),
        ]);
        return redirect()->route('dash');
    }
}
