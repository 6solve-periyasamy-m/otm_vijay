<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Repository\Authentication\CustomerAuthenticationRepository;
use App\Models\Customer\OrderCustomer;
use App\Models\Customer\Customer;

class Merchandise extends Component
{
    public Customer $customer;
    public $merchandise = [];

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
        //$this->merchandise = []; 
    }

    public function add()
    {
        $this->merchandise[] = [
            'id' => null,
            'category' => '',
            'size' => '',
            'other_details' => '',
        ];
    }

    public function remove($index)
    {
        unset($this->merchandise[$index]);
        $this->merchandise = array_values($this->merchandise);
    }

    public function inputChanged()
    {
        // no-op to prevent Livewire error
    }

    public function save()
    {
        $this->validate([
            'merchandise.*.category' => 'nullable|string|max:100',
            'merchandise.*.size' => 'nullable|string|max:100',
            'merchandise.*.other_details' => 'nullable|string|max:255',
        ]);

        // $existingIds = $this->customer->loyaltyNumbers()->pluck('id')->toArray();
        // $incomingIds = collect($this->loyalties)->pluck('id')->filter()->toArray();

        // // Delete removed
        // LoyaltyNumber::destroy(array_diff($existingIds, $incomingIds));

        // // Create or update
        // foreach ($this->loyalties as $loyalty) {
        //     $this->customer->loyaltyNumbers()->updateOrCreate(
        //         ['id' => $loyalty['id']],
        //         [
        //             'type' => $loyalty['type'],
        //             'details' => $loyalty['notes'],
        //             'number' => $loyalty['name'],
        //         ]
        //     );
        // }

        // $this->dispatch('loyalty-saved');
        session()->flash('success', 'Merchandise updated!');
    }

    public function render()
    {
        return view('livewire.customer.merchandise');
    }
}
