<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Repository\Authentication\CustomerAuthenticationRepository;


class Leftsidebar extends Component
{
    public $orderCustomer = null;
    public $upcomingOrders = [];
    public $branding = null;

    public function mount()
    {
        $this->orderCustomer = CustomerAuthenticationRepository::getCustomer();
        if ($this->orderCustomer) {
            $this->upcomingOrders = $this->orderCustomer->orders()
                ->with('tour')
                ->where('cancelled', false)
                ->orderBy('ordered_on')
                ->get();            
        }
        $this->branding = \App\Models\System\Brand::getSystemBrand();
    }

    public function render()
    {
        return view('livewire.customer.left-sidebar');
    }
}
