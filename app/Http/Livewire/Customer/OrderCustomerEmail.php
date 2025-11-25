<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Repository\Authentication\CustomerAuthenticationRepository;
use App\Models\Customer\OrderCustomer;
use App\Models\Customer\Customer;
use Illuminate\Support\Facades\Validator;


class OrderCustomerEmail extends Component
{
    public $orderCustomer;
    public $email;
    public $order_id;

    protected $rules = [
        'email' => 'nullable|email|max:255',
    ];

    public function mount($orderCustomer)
    {
        $this->orderCustomer = $orderCustomer;
        $this->email = $orderCustomer->customer->email_address ?? '';
        $this->order_id = $orderCustomer->order_id;
    }

    public function updateEmail()
    {
        $this->validate([
            'email' => 'required|email|unique:customers,email_address,' . $this->orderCustomer->customer->id,
        ]);

        if ($this->email) {
            $this->orderCustomer->customer->update(['email_address' => $this->email]);
            session()->flash('message', 'Email updated successfully!');
        } else {
            session()->flash('message', 'Email cannot be empty!');
        }
    }

    public function render()
    {
        return view('livewire.customer.order-customer-email');
    }
}
