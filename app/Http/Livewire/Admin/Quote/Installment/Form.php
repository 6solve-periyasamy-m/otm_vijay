<?php

namespace App\Http\Livewire\Admin\Quote\Installment;

use App\Http\Livewire\SendsEvents;
use App\Models\Quote\Quote;
use App\Models\Quote\QuoteInstallment;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents;

    /** @var Quote $quote */
    public Quote|int $quote;
    /** @var QuoteInstallment $quote */
    public QuoteInstallment|int|null $installment;

    public function mount(Quote|int $quote, QuoteInstallment|int|null $installment = null): void
    {
        $this->quote = Quote::getForMount($quote);
        $this->installment = QuoteInstallment::getForMount($installment);
    }

    public function save(): void
    {
        $this->validate();
        $this->installment->quote_id = $this->quote->id;
        $this->installment->save();
        $this->refreshPage();
        $this->emit('closeModal');
    }

    public function render()
    {
        return view('livewire.admin.quote.installment.form');
    }

    public function rules()
    {
        return [
            'installment.amount' => 'required|numeric|gt:0',
            'installment.percentage' => 'nullable|boolean',
            'installment.due_on' => 'required',
        ];
    }
}
