<?php

namespace App\Http\Livewire\Customer\Booking;

use App\Repository\Storage\Customer\Component\BookingComponent;
use Exception;
use Livewire\Component;

class ComponentCard extends Component
{
    public BookingComponent $component;
    public $upgrade;

    public function buyOne()
    {
        if ($this->component->owned) {
            $upgrade = $this->getUpgrade();
            if ($upgrade === null) {
                $this->showAlert('Please select an upgrade');
                return;
            }
            $success = $upgrade->purchaseForOne();
            if (!$success) {
                $this->showAlert('Upgrade Failed');
                return;
            }
            $this->component = $upgrade;
        } else {
            $this->component->purchaseForOne();
        }
        $this->render();
    }

    public function buyAll()
    {

    }

    public function sellOne()
    {

    }

    public function sellAll()
    {

    }

    private function getUpgrade(): BookingComponent|null
    {
        try {
            return BookingComponent::fromLivewire(json_decode($this->upgrade, true));
        } catch (Exception) {
            return null;
        }
    }

    private function showAlert(string $message)
    {
        $this->dispatchBrowserEvent('livewireAlert', ['message' => $message,]);
    }

    public function render()
    {
        return view('livewire.customer.booking.component-card');
    }
}
