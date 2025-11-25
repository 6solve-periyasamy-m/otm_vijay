<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Repository\Authentication\CustomerAuthenticationRepository;
use App\Models\Customer\OrderCustomer;
use App\Models\Customer\Customer;

class LoyaltyNumbers extends Component
{
    public Customer $customer;
    public $loyalties = [];

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
        //$this->loyalties = []; 
    }

    public function add()
    {
        $this->loyalties[] = [
            'id' => null,
            'type' => '',
            'notes' => '',
            'name' => '',
        ];
    }

    public function remove($index)
    {
        unset($this->loyalties[$index]);
        $this->loyalties = array_values($this->loyalties);
    }

    public function inputChanged()
    {
        // no-op to prevent Livewire error
    }

    public function save()
    {
        $this->validate([
            'loyalties.*.type' => 'nullable|string|max:100',
            'loyalties.*.notes' => 'nullable|string|max:255',
            'loyalties.*.name' => 'nullable|string|max:100',
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
        session()->flash('success', 'Loyalty numbers updated!');
    }

    public function render()
    {
        return view('livewire.customer.loyalty-numbers');
    }
}
