<?php

namespace App\Http\Livewire\Admin\Quote;

use App\Http\Livewire\Abstract\AccommodationByDateComponent;
use App\Models\Accommodation\Accommodation;
use App\Models\Quote\Quote;
use Carbon\Carbon;

class AccommodationSelector extends AccommodationByDateComponent
{
    public int $quote;
    public int $travellers;

    public function mount(Accommodation|int|null $accommodation = null, Carbon|string|null $start = null, Carbon|string|null $end = null, Quote|int|null $quote = null, int|null $travellers = 0)
    {
        parent::mount($accommodation, $start, $end);

        if ($quote instanceof Quote) {
            $this->quote = $quote->id;
        } else {
            $this->quote = $quote;
        }

        $this->travellers = $travellers;
    }

    public function save(): void
    {
        $quote = Quote::find($this->quote);
        if ($quote === null) { return; }
        $quote->accommodation()->delete();
        foreach ($this->fetchData() as $data) {
            if ($this->selected($data)) {
                $data->addToQuote($quote);
            }
        }
        $this->toast('Accommodation Saved Successfully', 'Successfully removed accommodation and added new ones to the quote', 'success');
    }

    public function getPackageType(): string
    {
        return 'Quote';
    }

    public function getReturnUrl(): string
    {
        return route('quotes.view', ['quote' => $this->quote]);
    }
}
