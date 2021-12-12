<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Repository\SettingsRepository;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    public static function getValidationRules() {
        return [
            'company_name' => 'required',
            'company_email' => 'required|email',
            'company_phone' => 'required',
            'company_vat' => 'required',
            'address_line_1' => 'required',
            'address_line_2' => 'required',
            'city' => 'required',
            'region' => 'required',
            'postcode' => 'required',
            'booking_prefix' => 'required',
            'atol_issuer' => 'required',
            'atol_number' => 'required',
            'company_logo' => 'nullable|image',
            'atol_stamp' => 'nullable|image',
            'currency_id' => 'required|exists:currencies,id',
            'stripe_key' => 'nullable',
            'date_format' => 'required',
        ];
    }

    public function edit() {
        return view('pages.settings.form');
    }

    public function update(Request $request) {
        $request->validate(SettingsController::getValidationRules());
        SettingsRepository::setAll([
            'company.name' => $request->input('company_name'),
            'company.contact.email' => $request->input('company_email'),
            'company.contact.phone' => $request->input('company_phone'),
            'company.vat' => $request->input('company_vat'),
            'company.address.line_1' => $request->input('address_line_1'),
            'company.address.line_2' => $request->input('address_line_2'),
            'company.address.city' => $request->input('city'),
            'company.address.region' => $request->input('region'),
            'company.address.postcode' => $request->input('postcode'),
            'booking.prefix' => $request->input('booking_prefix'),
            'atol.issuer' => $request->input('atol_issuer'),
            'atol.number' => $request->input('atol_number'),
            'billing.stripe.key' => $request->input('stripe_key'),
            'system.format.date' => $request->input('date_format'),
            'system.format.time' => $request->input('time_format'),
        ]);
        if ($request->has('company_logo')  && $request->file('company_logo') != null) {
            SettingsRepository::set('company.logo', $this->saveImage($request->file('company_logo')));
        }
        if ($request->has('atol_stamp') && $request->file('atol_stamp') != null) {
            SettingsRepository::set('atol.stamp', $this->saveImage($request->file('atol_stamp')));
        }
        $currency = Currency::where('id', '=', $request->input('currency_id'))->first();
        SettingsRepository::set('system.currency', $currency->code);
        return redirect()->route('dash');
    }

    private function saveImage($file) {
        return $file->storePublicly('uploads/images');
    }
}
