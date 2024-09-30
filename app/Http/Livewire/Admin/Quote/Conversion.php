<?php

namespace App\Http\Livewire\Admin\Quote;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Models\Quote\Quote;
use App\Repository\Storage\Quote\CustomerForConversion;
use Livewire\Component;

class Conversion extends Component
{
    use LivewireForm;

    public array $travellers = [];
    public Quote $quote;
    public int $paying;
    public int $travelling;
    public bool $verifyComponents = true;

    public function mount(Quote $quote, int $paying, int $travelling)
    {
        $this->quote = $quote;
        $this->paying = $paying;
        $this->travelling = $travelling;
        $this->travellers[] = ['id' => -1, 'name' => $quote->leadTraveller->name, 'paying' => $quote->leadTraveller->paying,  'travelling' => $quote->leadTraveller->travelling, 'items' => []];
        for ($x = 0; $x < $paying; $x++) {
            $this->travellers[] = ['id' => null, 'name' => null, 'paying' => true, 'travelling' => true, 'items' => []];
        }
        for ($x = 0; $x < $travelling; $x++) {
            $this->travellers[] = ['id' => null, 'name' => null, 'paying' => false, 'travelling' => true, 'items' => []];
        }
        $this->verifyAllComponents();
    }

    public function inputChanged(string|null $key = null): void
    {
        foreach ($this->travellers as $key => $traveller) {
            if (((int)$traveller['id'] ?? 0) > 0) {
                $customer = Customer::find($traveller['id']);
                $traveller['name'] = "$customer->title $customer->first_name $customer->last_name";
                $this->travellers[$key] = $traveller;
            }
        }
        $this->render();
    }

    public function getQuantity(string $type, int $id): int
    {
        $quantity = 0;
        foreach ($this->travellers as $key => $traveller) {
            $quantity += $this->hasComponent($key, $type, $id) ? 1 : 0;
        }
        return $quantity;
    }

    public function orderExists()
    {
        return Order::where('booking_reference', '=', $this->quote->reference)->first() !== null;
    }

    public function hasComponent(int $traveller, string $type, int $id): bool
    {
        if (array_key_exists($traveller, $this->travellers)) {
            foreach ($this->travellers[$traveller]['items'] as $key => $item) {
                if ($item['type'] === $type && $item['id'] === $id) {
                    return true;
                }
            }
        }
        return false;
    }

    public function toggleComponent(int $traveller, string $type, int $id): void
    {
        if (array_key_exists($traveller, $this->travellers)) {
            foreach ($this->travellers[$traveller]['items'] as $key => $item) {
                if ($item['type'] === $type && $item['id'] === $id) {
                    unset($this->travellers[$traveller]['items'][$key]);
                    $this->verifyAllComponents();
                    $this->render();
                    return;
                }
            }
            $this->travellers[$traveller]['items'][] = ['type' => $type, 'id' => $id,];
        }
        $this->verifyAllComponents();
        $this->render();
    }

    public function verifyAllComponents(): void
    {
        $belowAbove = false;
        foreach ($this->quote->repository->getActivityBelowQuantity($this->paying + $this->travelling + 1) as $component) {
            if ($this->getQuantity('activity', $component->id) !== $component->quantity) {
                $belowAbove = true;
                break;
            }
        }
        if ($belowAbove) { $this->verifyComponents = true; return; }
        foreach ($this->quote->repository->getFlightBelowQuantity($this->paying + $this->travelling + 1) as $component) {
            if ($this->getQuantity('flight', $component->id) !== $component->quantity) {
                $belowAbove = true;
                break;
            }
        }
        if ($belowAbove) { $this->verifyComponents = true; return; }
        foreach ($this->quote->repository->getTransportBelowQuantity($this->paying + $this->travelling + 1) as $component) {
            if ($this->getQuantity('transport', $component->id) !== $component->quantity) {
                $belowAbove = true;
                break;
            }
        }
        if ($belowAbove) { $this->verifyComponents = true; return; }
        foreach ($this->quote->repository->getMerchandiseBelowQuantity($this->paying + $this->travelling + 1) as $component) {
            if ($this->getQuantity('merchandise', $component->id) !== $component->quantity) {
                $belowAbove = true;
                break;
            }
        }
        $this->verifyComponents = $belowAbove;
    }

    public function convert()
    {
        $customers = [];
        foreach ($this->travellers as $traveller) {
            if ($traveller['id'] === -1) {
                $lead = (new CustomerForConversion($traveller['paying'], $traveller['travelling']))->setCustomer($this->quote->leadTraveller->customer_id)->setComponents($traveller['items']);
                continue;
            }
            $customers[] = (new CustomerForConversion($traveller['paying'], $traveller['travelling']))->setCustomer($traveller['id'])->setComponents($traveller['items']);
        }
        return redirect()->route('orders.view', ['order' => $this->quote->repository->convert($lead, $customers),]);
    }

    public function render()
    {
        return view('livewire.admin.quote.conversion');
    }
}
