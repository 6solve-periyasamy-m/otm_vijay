<?php

namespace App\Http\Livewire\Admin\Quote\PricePoint;

use App\Http\Livewire\Abstract\CurrencyColumn;
use App\Models\Quote\QuotePricePoint;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Table extends LivewireDatatable
{
    public int $quote;

    public function builder()
    {
        return QuotePricePoint::query()
            ->where('quote_id', '=', $this->quote)
            ->orderBy('quantity');
    }

    public function columns()
    {
        return [
            NumberColumn::name('quantity')
                ->label('Quantity')
                ->sortable()
                ->editable(),
            CurrencyColumn::name('price_per_person')
                ->label('Price Per Person')
                ->sortable()
                ->editable(),
        ];
    }
}