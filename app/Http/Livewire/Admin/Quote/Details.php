<?php

namespace App\Http\Livewire\Admin\Quote;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Quote\Quote;
use Livewire\Component;

class Details extends Component
{
    use LivewireForm, SendsEvents;

    public Quote $quote;

    /** @var string|int $paying The number of paying travellers. */
    public string|int $paying = 0;

    /** @var string|int $travelling The number of non-paying travellers. */
    public string|int $travelling = 0;

    public function mount(Quote $quote)
    {
        $this->quote = $quote;
        $this->paying = $this->quote->paying ?? 0;
        $this->travelling = $this->quote->travelling ?? 0;
    }

    public function inputChanged(?string $key = null)
    {
        $this->save();
    }

    public function save()
    {
        $this->quote->paying = (int)$this->paying;
        $this->quote->travelling = (int)$this->travelling;
        $success = $this->quote->save();
        if ($success) {
            $this->toast('Quote Saved Successfully', 'Successfully saved the changes you have made.', 'success');
        } else {
            $this->toast('Quote Saving Failed', 'Failed to save the changes you have made. Please refresh and try again.', 'danger');
        }
    }

    // public function silentSave()
    // {
    //     $this->quote->paying = (int)$this->paying;
    //     $this->quote->travelling = (int)$this->travelling;
    //     $this->quote->save();
    // }

    public function render()
    {
        return view('livewire.admin.quote.details');
    }

    public function preview()
    {
        $paying = $this->quote->paying + ($this->quote->leadTraveller?->paying ? 1 : 0);
        if ($this->quote->repository->getPricePerPerson($paying) === null) {
            $this->toast('Failed to Send Quote', "No price point exists for $paying paying travellers", 'danger');
            return;
        }
        $this->openInNewTab(url()->route('quotes.preview', $this->getUrlArray()));
    }

    public function openEmailModal()
    {
        $paying = $this->quote->paying + ($this->quote->leadTraveller?->paying ? 1 : 0);
        $travelling = $this->quote->travelling + ($this->quote->leadTraveller?->travelling ? 1 : 0);
        $this->emitTo('admin.quote.send-popup-mail', 'openEmailModal', $this->quote->id, $paying, $travelling);
    }

    public function convert()
    {
        return redirect()->route('quotes.conversion', $this->getUrlArray());
    }

    public function costs()
    {
        return redirect()->route('quotes.costing', $this->getUrlArray());
    }

    public function incrementPaying(int $value): void
    {
        $this->paying += $value;
        $this->paying = max(0, (int)$this->paying);
        $this->Save();
    }

    public function incrementTravelling(int $value): void
    {
        $this->travelling += $value;
        $this->travelling = max(0, (int)$this->travelling);
        $this->save();
    }

    private function getUrlArray(): array
    {
        $paying = $this->quote->paying + ($this->quote->leadTraveller?->paying ? 1 : 0);
        $travelling = $this->quote->travelling + ($this->quote->leadTraveller?->travelling ? 1 : 0);
        return ['quote' => $this->quote, 'paying' => $paying, 'travelling' => $travelling];
    }

    public function rules()
    {
        return [
            'quote.name' => 'required|string|min:3',
        ];
    }
}
