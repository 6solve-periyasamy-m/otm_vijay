<?php

namespace App\Http\Livewire\Admin\Quote\Prospect;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Customer\Customer;
use App\Models\Quote\Quote;
use App\Models\Quote\QuoteProspect;
use App\Repository\Model\Customer\CustomerRepository;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents, LivewireForm;

    public Quote|int|null $quote;
    public QuoteProspect|int|null $prospect;
    public Customer $customer;

    public function mount(Quote|int|null $quote = null, QuoteProspect|int|null $prospect = null)
    {
        $prospect = QuoteProspect::getForMount($prospect);
        $quote = Quote::getForMount($quote);
        if ($prospect->id !== null) {
            $this->prospect = $prospect;
            $this->customer = $prospect->customer;
            $this->quote = Quote::find($prospect->quote_id);
        }else if ($quote->id !== null) {
            $this->quote = $quote;
            $this->prospect = QuoteProspect::make(['quote_id' => $this->quote->id]);
            $this->customer = Customer::make();
        } else {
            $this->toast('Quote not found', 'Error loading form', 'error');
            $this->closeModal();
        }
    }

    public function updated($key, $value): void
    {
        if ($key === 'prospect.customer_id') {
            $customer = Customer::find($value);
            if ($customer !== null) {
                $this->customer = $customer;
                $this->prospect->customer_id = $customer->id;
            } else {
                $this->customer = Customer::make();
                $this->prospect->customer_id = null;
            }
        }
    }

    public function save()
    {
        if ($this->customer->id === null) {
            $this->customer = CustomerRepository::createSimple(['first_name' => $this->customer->first_name, 'last_name' => $this->customer->last_name]);
        }
        $this->customer->save();
        $this->prospect->customer_id = $this->customer->id;
        $this->prospect->quote_id = $this->quote->id;
        $this->prospect->paying = $this->prospect->paying ?? false;
        $this->prospect->travelling = $this->prospect->travelling ?? false;
        $this->prospect->save();
        $this->toast('Prospect Saved Successfully', 'Successfully saved the quote traveller');
        $this->refreshPage();
    }

    public function render()
    {
        return view('livewire.admin.quote.prospect.form');
    }

    public function rules()
    {
        return [
            'prospect.customer_id' => 'nullable|exists:customers,id',
            'customer.first_name' => 'required_without:prospect.customer_id|string',
            'customer.last_name' => 'required_without:prospect.customer_id|string',
            'prospect.paying' => 'nullable|boolean',
            'prospect.travelling' => 'nullable|boolean',
        ];
    }
}
