<?php

namespace App\Http\Livewire\Admin\Order;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use Livewire\Component;
use App\Models\Customer\Organization;
use App\Models\Order\Order;

class OrganizationSelector extends Component
{
    use LivewireForm, SendsEvents;

    public ?Organization $organization = null;
    public ?Order $order = null;

    public float|null $commission = null;
    public int|null $organization_id = null;
    public int|null $agent_id = null;

    public function mount($order = null)
    {
        $this->order = $order instanceof Order ? $order : null;
    }

    public function inputChanged(?string $key = null)
    {
        if ($key === 'organization_id') {
            $commission = $this->organization_id ? Organization::find($this->organization_id)?->commission : null;
            $this->commission = $commission;       
        }
    }

    public function render()
    {
        return view('livewire.admin.order.organization-selector', ['order' => $this->order ,]);
    }
}
