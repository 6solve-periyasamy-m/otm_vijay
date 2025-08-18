<x-admin.section.card>
    <div class="row">
        <x-livewire.input.select.customer name="prospect.customer_id" value="{{ $customer->id }}" label="Existing Customer" clear />
        <hr class="splitter" />
        <x-livewire.input width="6" label="First Name" wire:model="customer.first_name" />
        <x-livewire.input width="6" label="Last Name" wire:model="customer.last_name" />
        <hr class="splitter" />
        <x-livewire.input.checkbox width="6" wire:model="prospect.paying" label="Paying?" />
        <x-livewire.input.checkbox width="6" wire:model="prospect.travelling" label="Travelling?" />
        <div class="col-12">
            <button wire:click="save" class="btn btn-success">
                {{ Icon::save() }} Save
            </button>
        </div>
    </div>
</x-admin.section.card>