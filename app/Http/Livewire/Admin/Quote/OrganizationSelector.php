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

    public function mount($quote = null, $organization_id = null, $agent_id = null, $commission = null)
    {
        $this->quote = $quote instanceof Quote ? $quote : null;
        $this->organization_id = $organization_id;
        $this->commission = $commission;
        $this->agent_id = $agent_id;

        if ($this->organization_id) {
            $organization = Organization::find($this->organization_id);
            $this->commission = $organization?->commission ?? $this->commission;
        }
    }

    public function updatedQuoteOrganizationId($value)
    {
        if ($value) {
            $organization = Organization::find($value);
            $this->commission = $organization?->commission ?? 0;
        } else {
            $this->commission = null;
        }
    }

    public function rules()
    {
        return [
            'organization_id' => 'nullable|exists:organizations,id',
            'agent_id' => 'required_if:organization_id,!null|exists:agents,id',
            'commission' => 'nullable|numeric|min:0',
        ];
    }

    public function render()
    {
        return view('livewire.admin.quote.organization-selector', ['quote' => $this->quote ,]);
    }
}
