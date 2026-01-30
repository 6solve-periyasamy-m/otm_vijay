<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Location\Currency;
use App\Models\System\ConversionRate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Customer\BookingV3Controller;


class AppConfigController extends ApiController
{
    
    /**
     * GET /api/booking/app-config
     * Global frontend configuration
     */
    public function index(): JsonResponse
    {
        $allowedCurrencies = BookingV3Controller::ALLOWED_CURRENCIES; // e.g. AUD

        return response()->json([
            'success' => true,

            'currency' => [
                'default' => $allowedCurrencies,
                'list' => $this->currencyList(),
                'rates' => $this->fxRates(),
            ],

            'success_redirect' => [
                'booking' => setting('booking.success.redirect', ''),
            ],

            'stripe' => [
                'publishable_aud' => config('app.gateways.stripe.currencies.aud.client', config('app.gateways.stripe.publishable')),
                'publishable_usd' => config('app.gateways.stripe.currencies.USD.client', config('app.gateways.stripe.publishable')),
            ],

            /*'contact' => $this->contactDetails($defaultCurrency), */
        ]);
    }

    
    /**
     * Currency list
     */
    protected function currencyList(): array
    {
        return Currency::query()
            ->orderByDesc('priority')
            ->get(['id', 'code', 'name', 'symbol'])
            ->toArray();
    }
    
    /**
     * FX rates
     */
    protected function fxRates(): array
    {
        return Cache::remember('fx_matrix', 3600, function () {
            return ConversionRate::query()
                ->get()
                ->map(fn ($rate) => [
                    'from' => $rate->from->code,
                    'to' => $rate->to->code,
                    'rate' => (float) $rate->rate,
                    'sales' => $rate->sales,
                ])
                ->toArray();
        });
    }
}
