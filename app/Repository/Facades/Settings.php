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

    public function getNullTaxBracket(): TaxBracket
    {
        return new TaxBracket([
            'id' => null,
            'name'=> __('custom.tax.null.name'),
            'description'=> __('custom.tax.null.description'),
            'rate' => null,
        ]);
    }
}
