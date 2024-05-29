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

    public function __construct()
    {
        $this->repository = SettingsRepository::getInstance();
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
    }

    public function setAll(array $keys): void
    {
        $this->repository->setAll($keys);
    }

    public function authorize(string $key, int $seconds): void
    {
        $this->repository->authorize($key, $seconds);
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

    public function getDefaultInstallments(): array
    {
        return json_decode($this->get('system.installments.default', "{}"), true);
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
        return TaxBracket::find(static::get('system.tax.bracket')) ?? static::getNullTaxBracket();
    }
    
    public function getConversionRate(Currency|string $from, Currency|string $to): float|null
    {
        if (is_string($from)) {
            $from = Currency::where('code', '=', $from)->first();
        }
        if (is_string($to)) {
            $to = Currency::where('code', '=', $to)->first();
        }
        if ($from === null || $to === null) return null;
        if ($from->id === $to->id) return 1;
        return ConversionRate::where('from_currency_id', '=', $from->id)
            ->where('to_currency_id', '=', $to->id)
            ->first()?->rate;
    }

    /**
     * Convert any amount from any currency to any other known in the system
     * @param float $amount The amount you with to convert
     * @param Currency|string $from The currency to convert from
     * @param Currency|string|null $to The currency to convert to (leave null for system)
     * @return float|null The converted amount, or null if conversion is unavailable due to missing rate or the same currency
     */
    public function convertCurrency(float $amount, Currency|string $from, Currency|string|null $to = null): ?float
    {
        $from = is_string($from) ? $from : $from->code;
        $to = $to ?? setting('system.currency', 'GBP');
        $to = is_string($to) ? $to : $to->code;
        if ($from !== $to) {
            $conversion = Settings::getConversionRate($from, $to);
            if ($conversion !== null) {
                return sigfig($amount * $conversion);
            } else {
                return null;
            }
        } else {
            return null;
        }
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
        if (\Feature::someAreActive(['bleeding-edge', 'is-kpt'])) {
            $styles[2] = 'Alternative Style (Under Development)';
        }
        return $styles;
    }

    public function availableQuoteStyles(): array
    {
        $styles = [1 => 'Default Quote Style',];
        if (\Feature::someAreActive(['bleeding-edge', 'is-kpt'])) {
            $styles[2] = 'Alternative Style (Under Development)';
        }
        return $styles;
    }
}
