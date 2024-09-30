<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\Location\Currency;
use App\Models\System\LargeTextTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Settings;

class SettingsController extends Controller
{
    public static function getValidationRules(): array
    {
        return [
            'company_name' => 'required',
            'company_email' => 'required|email',
            'company_phone' => 'required',
            'company_vat' => 'required',
            'address_line_1' => 'required',
            'address_line_2' => 'required',
            'city' => 'required',
            'region' => 'required',
            'country' => 'required',
            'postcode' => 'required',
            'booking_prefix' => 'required',
            'quote_prefix' => 'required',
            'company_logo' => 'nullable|image',
            'atol_stamp' => 'nullable|image',
            'atol_filter' => 'required',
            'currency_id' => 'required|exists:currencies,id',
            'stripe_key' => 'nullable',
            'date_format' => 'required',
            'year_start' => 'required|date',
        ];
    }

    public function edit() {
        return view('pages.admin.system.settings');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate(self::getValidationRules());
        Settings::setAll([
            'company.name' => $request->input('company_name'),
            'company.contact.email' => $request->input('company_email'),
            'company.contact.phone' => $request->input('company_phone'),
            'company.url' => $request->input('company_url'),
            'company.vat' => $request->input('company_vat'),
            'company.address.line_1' => $request->input('address_line_1'),
            'company.address.line_2' => $request->input('address_line_2'),
            'company.address.city' => $request->input('city'),
            'company.address.region' => $request->input('region'),
            'company.address.country' => $request->input('country'),
            'company.address.postcode' => $request->input('postcode'),
            'booking.prefix' => $request->input('booking_prefix'),
            'quote.prefix' => $request->input('quote_prefix'),
            'booking.success.redirect' => $request->input('booking_redirect'),
            'payment.success.redirect' => $request->input('payment_redirect'),
            'purchase.addon.success.redirect' => $request->input('addon_redirect'),
            'purchase.upgrade.success.redirect' => $request->input('upgrade_redirect'),
            'atol.issuer' => $request->input('atol_issuer'),
            'atol.number' => $request->input('atol_number'),
            'atol.filter' => $request->input('atol_filter'),
            'atol.enabled' => $request->input('atol_enabled') === 'on' ? 1 : 0,
            'atol.year.start' => $request->input('atol_start'),
            'system.mail.enabled' => $request->input('mail_enabled') === 'on' ? 1 : 0,
            'billing.stripe.key' => $request->input('stripe_key'),
            'system.format.date' => $request->input('date_format'),
            'system.format.time' => $request->input('time_format'),
            'social.facebook' => $request->input('social_facebook'),
            'social.twitter' => $request->input('social_twitter'),
            'social.instagram' => $request->input('social_instagram'),
            'company.bank_transfer' => $request->input('bank_transfer'), //for invoice: Bank Transfer
            'system.year.start' => $request->input('year_start'),
            'system.historic' => $request->input('historic'),
            'payment.required' => $request->input('payment_required') === 'on' ? 1 : 0,
            'installments.force' => $request->input('force_installments') === 'on' ? 1 : 0,
            'mail.bcc-sender' => $request->input('bcc_sender') === 'on' ? 1 : 0,
            'mail.bcc-consultant' => $request->input('bcc_consultant') === 'on' ? 1 : 0,
            'quote.convert.reference' => $request->input('quote_reference') === 'on' ? 1 : 0,
            'components.lock' => $request->input('components_lock'),
            'passport.lock' => $request->input('passport_lock'),
            'passport.unlock' => $request->input('passport_unlock'),
            'order-notes.lock' => $request->input('order_notes_lock'),
            'order-notes.unlock' => $request->input('order_notes_unlock'),
            'accommodation.lock' => $request->input('accommodation_lock'),
            'accommodation.unlock' => $request->input('accommodation_unlock'),
            'activity.lock' => $request->input('activity_lock'),
            'activity.unlock' => $request->input('activity_unlock'),
            'flight.lock' => $request->input('flight_lock'),
            'flight.unlock' => $request->input('flight_unlock'),
            'transport.lock' => $request->input('transport_lock'),
            'transport.unlock' => $request->input('transport_unlock'),
            'invoice.style' => $request->input('invoice_format'),
            'quote.style' => $request->input('quote_format'),
            'itinerary.style' => $request->input('itinerary_format'),
            'customization.documentation.colors' => $request->input('document_css'),
        ]);
        if ($request->has('company_logo')  && !empty($request->file('company_logo'))) {
            Settings::set('company.logo', $this->saveImage($request->file('company_logo')));
        }
        if ($request->has('atol_stamp') && !empty($request->file('atol_stamp'))) {
            Settings::set('atol.stamp', $this->saveImage($request->file('atol_stamp')));
        }
        $currency = Currency::where('id', '=', $request->input('currency_id'))->first();
        Settings::set('system.currency', $currency?->code);
        return redirect()->route('settings.edit');
    }

    public function template()
    {
        return view('pages.admin.system.template.view');
    }

    public function editTemplate(LargeTextTemplate|null $template = null)
    {
        return view('pages.admin.system.template.form', ['template' => $template,]);
    }

    public function mail()
    {
        return view('pages.admin.system.mail');
    }

    public function import()
    {
        return view('pages.admin.system.import');
    }

    public function authorizeReminders(int $days): RedirectResponse
    {
        Settings::authorize('authorization.reminders', $days < 0 ? -1 : $days * 24 * 60 * 60);
        return back();
    }

    private function saveImage($file)
    {
        return $file->storePublicly('uploads/images');
    }
}
