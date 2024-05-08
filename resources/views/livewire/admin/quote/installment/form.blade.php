<x-admin.section.card>
    <div class="row">
        <x-livewire.input type="date" wire:model="installment.due_on" required width="3" label="Due On" />
        <x-livewire.input wire:model="installment.amount" required width="3" label="Amount" />
        <x-livewire.input.checkbox wire:model="installment.percentage" required width="3" label="Is Percentage" />
        <div class="col-xl-3">
            <button class="btn btn-success" wire:click="save">
                Save
            </button>
        </div>
    </div>
</x-admin.section.card>
