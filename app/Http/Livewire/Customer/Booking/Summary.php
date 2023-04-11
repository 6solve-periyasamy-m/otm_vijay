<?php

namespace App\Http\Livewire\Customer\Booking;

use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;

class Summary extends Component
{
    public Booking $booking;
    public BookingTraveller $active;
    public bool $accepted;
    public float $amount;

    protected $messages = [
        'amount.gt' => 'You must pay the minimum deposit',
        'amount.lt' => 'You cannot pay more than you owe'
    ];

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->active = $booking->leadTraveller;
        $this->accepted = false;
        $this->amount = $booking->due_today;
    }

    public function pay(): RedirectResponse
    {
        $this->validate();

        return redirect()->to($this->booking->repository->getGatewayUrl($this->amount));
    }

    public function changeActive($traveller)
    {
        $selected = $this->booking->travellers()->where('id', '=', $traveller)->first();
        if ($selected !== null) {
            $this->active = $selected;
        }
        $this->render();
    }

    public function accept()
    {
        $this->accepted = true;
        $this->render();
    }

    public function render()
    {
        return view('livewire.customer.booking.summary');
    }

    protected function rules()
    {
        $max = min($this->booking->total_cost, 999_999);
        $min = max($this->booking->due_today, 0.31);
        return [
            'amount' => "required|numeric|lte:$max|gte:$min",
        ];
    }
}
