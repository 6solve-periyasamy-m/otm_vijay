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
        $this->emit('componentsChanged', $this->component->traveller->id);
        $this->render();
    }

    public function buyAll()
    {
        if ($this->component->tour_component_type !== 'Add-on') {
            $upgrade = $this->getUpgrade();
            if ($upgrade === null) {
                $this->showAlert('Please select an upgrade');
                return;
            }
            $success = $upgrade->purchaseForAll();
            if (!$success) {
                $this->showAlert('Upgrade Failed');
                return;
            }
            $this->component = $upgrade;
        } else {
            $this->component->purchaseForAll();
        }
        $this->emit('allComponentsChanged', $this->component->traveller->booking->id);
        $this->render();
    }

    public function sellOne()
    {
        $success = $this->component->sellForOne();
        if (!$success) {
            $this->showAlert('Failed to remove');
        }
        $this->emit('componentsChanged', $this->component->traveller->id);
        $this->render();
    }

    public function sellAll()
    {
        $success = $this->component->sellForAll();
        if (!$success) {
            $this->showAlert('Failed to remove');
        }
        $this->emit('allComponentsChanged', $this->component->traveller->booking->id);
        $this->render();
    }

    public function canUpgradeForOne(): bool
    {
        $upgrade = $this->component->tour_component_type === 'Add-on' ? $this->component : $this->getUpgrade();
        return $upgrade?->canBookForOne() ?? false;
    }

    public function canUpgradeForAll(): bool
    {
        $upgrade = $this->component->tour_component_type === 'Add-on' ? $this->component : $this->getUpgrade();
        return $upgrade?->canBookForAll() ?? false;
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
