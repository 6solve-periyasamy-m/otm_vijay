<div class="py-md-3 px-md-4">
    <div class="row">
        <x-livewire.input.select.organization name="organization_id" label="Organization (Optional)" value="{{ $order?->organization_id }}" width="5"/>
        <x-livewire.input wire:model="commission" name="commission" label="Commission (%)" width="2" value="{{ $order?->commission }}" />      
        <x-livewire.input.select.agent name="agent_id" label="Agent (Optional)" value="{{ $order?->agent_id }}" width="5"/>  
    </div>
</div>