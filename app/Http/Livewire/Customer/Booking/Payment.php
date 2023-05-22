<?php

namespace App\Http\Livewire\Customer\Booking;

use App\Models\Booking\Booking;
use Livewire\Component;

class Payment extends Component
{
    public Booking $booking;
    public bool $accepted;
    public $amount;

    protected $messages = [
        'amount.gte' => 'You must pay the minimum deposit',
        'amount.lte' => 'You cannot pay more than you owe'
    ];

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->accepted = false;
        $this->amount = $booking->due_today;
    }

    public function pay()
    {
        $this->amount = sigfig((float)preg_replace('/[^0-9.]/', '', $this->amount));
        $this->validate();

        return redirect()->to($this->booking->repository->getGatewayUrl($this->amount));
    }

    public function accept()
    {
        $this->accepted = true;
        $this->render();
    }

    public function render()
    {
        return view('livewire.customer.booking.payment');
    }

    protected function rules()
    {
        $max = min($this->booking->repository->getTotalCost(), 999_999);
        $min = max($this->booking->due_today, 0.31);
        return [
            'amount' => "required|numeric|lte:$max|gte:$min",
        ];
    }
}
