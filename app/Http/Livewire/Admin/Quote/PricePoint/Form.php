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
    public int|null $quantity = null;
    public int|null $amount = null;

    public function mount(Quote $quote)
    {
        $this->quote = Quote::getForMount($quote);
    }

    public function save()
    {
        $this->validate();
        $point = $this->quote->pricePoints()->where('quantity', '=', $this->quantity)->first();
        if ($point === null) {
            $point = new QuotePricePoint(['quantity' => $this->quantity,]);
        }
        $point->price_per_person = $this->amount;
        $this->quote->pricePoints()->save($point);
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
            'quantity' => 'required|integer|gt:0',
            'amount' => 'required|numeric|gt:0',
        ];
    }
}
