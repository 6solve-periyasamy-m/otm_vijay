<?php

namespace App\Repository\Facades;

use App\Models\Location\Country;
use App\Models\Location\Currency;
use App\Models\System\ConversionRate;
use App\Models\System\TaxBracket;
use App\Repository\SettingsRepository;
use Carbon\Carbon;

class Settings
{
    private SettingsRepository $repository;
    private bool $renew = false;
    private Currency|null $currency = null;

    public function __construct()
    {
        $this->repository = SettingsRepository::getInstance();
    }

    public function refresh(): void
    {
        $this->repository = SettingsRepository::getInstance(true);
    }

    public function forceRenewal(): void
    {
        $this->renew = true;
    }

    public function get($key, $default = null): ?string
    {
        return $this->repository->getOrDefault($key, $default);
    }

    public function getBoolean($key, $default = false): bool
    {
        return $this->repository->getBoolean($key, $default);
    }

    public function set($key, $value): void
    {
        $this->repository->set($key, $value);
        $this->renew && $this->refresh();
    }

    public function setAll(array $keys): void
    {
        $this->repository->setAll($keys);
        $this->renew && $this->refresh();
    }

    public function authorize(string $key, int $seconds): void
    {
        $this->repository->authorize($key, $seconds);
        $this->renew && $this->refresh();
    }

    public function authorized(string $key): bool
    {
        return $this->repository->authorized($key);
    }

    public function isLocked(string $key, Carbon $from, Carbon $to): bool
    {
        return $from->subDays(setting("{$key}.lock", 30))->lte(now()) && $to->addDays(setting("{$key}.unlock", 0))->gte(now());
    }

    /**
     * @return int The country id of the filter, or 0 if the filter is disabled
     */
    public function atolFilter(): int
    {
        $filter = $this->get('atol.filter');
        if ($filter === null) {
            $filter = Country::where('name', 'LIKE', 'United Kingdom')->first()?->id;
            if ($filter !== null) {
                $this->set('atol.filter', $filter);
            }

        }
        return $filter ?? 0;
    }

    /**
     * Returns the system currency
     *
     * @return Currency|null
     */
    public function currency(): Currency|null
    {
        if ($this->currency === null) {
            $this->currency = Currency::where('code', '=', $this->get('system.currency', 'GBP'))->first();
        }
        return $this->currency;
    }

    public function getDefaultInstallments(): array
    {
        $installments = json_decode($this->get('system.installments.default', "{}"), true);
        krsort($installments);
        return $installments;
    }

    public function setDefaultInstallment(int $days, float|null $percentage): void
    {
        $items = $this->getDefaultInstallments();
        if ($percentage === null) {
            unset($items[$days]);
        } else {
            $items[$days] = $percentage;
        }
        $this->set('system.installments.default', json_encode($items));
    }

    public function getTaxBracket(): TaxBracket
    {
        return TaxBracket::find($this->get('system.tax.bracket')) ?? $this->getNullTaxBracket();
    }
    
    public function getConversionRate(Currency|string|null $from, Currency|string|null $to): float|null
    {
        if (is_string($from)) { $from = Currency::where('code', '=', $from)->first(); }
        if (is_string($to)) { $to = Currency::where('code', '=', $to)->first(); }

        if ($from === null || $to === null) { return 1; }
        if ($from->id === $to->id) { return 1; }

        return ConversionRate::where('from_currency_id', '=', $from->id)
            ->where('to_currency_id', '=', $to->id)
            ->first()?->rate;
    }

    /**
     * Convert any amount from any currency to any other known in the system
     * @param float $amount The amount you with to convert
     * @param Currency|string|null $from The currency to convert from
     * @param Currency|string|null $to The currency to convert to (leave null for system)
     * @return float|null The converted amount, or null if conversion is unavailable due to missing rate or the same currency
     */
    public function convertCurrency(float $amount, Currency|string|null $from, Currency|string|null $to = null): ?float
    {
        if ($from === null) { return null; }
        $from = is_string($from) ? $from : $from->code;
        $to = $to ?? setting('system.currency', 'GBP');
        $to = is_string($to) ? $to : $to->code;
        if ($from !== $to) {
            $conversion = $this->getConversionRate($from, $to);
            if ($conversion !== null) {
                return sigfig($amount * $conversion);
            }
        }
        return null;
    }

    public function getNullTaxBracket(): TaxBracket
    {
        return new TaxBracket([
            'id' => null,
            'name'=> __('custom.tax.null.name'),
            'description'=> __('custom.tax.null.description'),
            'rate' => null,
        ]);
    }

    public function availableInvoiceStyles(): array
    {
        $styles = [1 => 'Default Invoice Style',];
        if (config('app.features.bleeding-edge') || config('app.features.kpt')) {
            $styles[2] = 'Alternative Style (Under Development)';
        }
        return $styles;
    }

    public function availableQuoteStyles(): array
    {
        $styles = [1 => 'Default Quote Style',];
        if (config('app.features.bleeding-edge') || config('app.features.kpt')) {
            $styles[2] = 'Alternative Style (Under Development)';
        }
        return $styles;
    }

    public function availableItineraryStyles(): array
    {
        $styles = [1 => 'Default Itinerary Style',];
        if (config('app.features.bleeding-edge') || config('app.features.kpt')) {
            $styles[2] = 'Alternative Style (Under Development)';
        }
        return $styles;
    }

    public function defaultQuoteExpiry(): string
    {
        $days = (int)$this->get('system.quote.expiry', -1);
        if ($days >= 0) {
            return now()->addDays($days)->format('Y-m-d');
        }
        return "";
    }

    public function defaultDocumentationColors(): string
    {
        return
            ':root {
                --text-color: #1A1A1A;
                --document-background: #FFFFFF;
                --divider-background-color: #353535;
                --divider-text-color: #FFFFFF;
                --document-title-color: #E95B15;
                --document-title-outline: #E95B15;
                --reference-bubble-background: #353535;
                --reference-bubble-text: #FFFFFF;
                --header-background: #E95B15;
                --header-title-color: #353535;
                --header-data-color: #FFFFFF;
                --alt-background-color: #F8DED0;
                --section-bubble-background: #E95B15;
                --section-bubble-text: #FFFFFF;
                --itinerary-item-title: #E95B15;
                --table-background-color: #F8DED0;
                --table-border-color: #353535;
                --table-header-background: #353535;
                --table-header-text: #FFFFFF;
            }';
    }
}
