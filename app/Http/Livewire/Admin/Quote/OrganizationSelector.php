<?php

namespace App\Http\Livewire\Admin\Quote;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use Livewire\Component;
use App\Models\Customer\Organization;
use App\Models\Quote\Quote;

class OrganizationSelector extends Component
{
    use LivewireForm, SendsEvents;

    public ?Organization $organization = null;
    public ?Quote $quote = null;

    public float|null $commission = null;
    public int|null $organization_id = null;
    public int|null $agent_id = null;

    public function mount($quote = null)
    {
        $this->quote = $quote instanceof Quote ? $quote : null;
    }

    public function inputChanged(?string $key = null)
    {
        if ($key === 'organization_id') {
            $commission = $this->organization_id ? Organization::find($this->organization_id)?->commission : $this->commission;
            $this->commission = $commission;
        }
    }

    public function render()
    {
        return view('livewire.admin.quote.organization-selector', ['quote' => $this->quote ,]);
    }
}
