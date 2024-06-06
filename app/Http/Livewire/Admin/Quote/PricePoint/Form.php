<?php

namespace App\Http\Livewire\Admin\Quote\PricePoint;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Quote\Quote;
use App\Models\Quote\QuotePricePoint;
use Livewire\Component;

class Form extends Component
{
    use SendsEvents;
    use LivewireForm;

    public Quote $quote;
    public QuotePricePoint|null $pricePoint = null;

    public function mount(Quote $quote)
    {
        $this->pricePoint = new QuotePricePoint(['quote_id' => $quote->id,]);
    }

    public function save()
    {
        $this->validate();
        $this->quote->pricePoints()->save($this->pricePoint);
        $this->refresh();
    }

    public function refresh()
    {
        $this->refreshTables();
    }

    public function render()
    {
        return view('livewire.admin.quote.price-point.form');
    }

    public function rules()
    {
        return [
            'pricePoint.quantity' => 'required|integer|gt:0',
            'pricePoint.price_per_person' => 'required|numeric|gt:0',
        ];
    }
}
