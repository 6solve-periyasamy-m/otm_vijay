<div class="py-md-3 px-md-3">
    <div class="row">
        <x-livewire.input.select.organization name="organization_id" label="Organization (Optional)" value="{{ $quote?->organization_id }}" width="5"/>
        <x-livewire.input wire:model="commission" name="commission" label="Commission (%)" width="2" value="{{ $quote?->commission }}" />      
        <x-livewire.input.select.agent name="agent_id" label="Agent (Optional)" value="{{ $quote?->agent_id }}" width="5"/>  
    </div>
</div>