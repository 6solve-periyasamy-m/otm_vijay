<x-admin.section.card>
    <div class="row">
        <x-livewire.input prepend="-" label="Amount" wire:model="amount" width="10" />
        <div class="col-xl-2 my-auto">
            <button class="btn btn-primary" wire:click="submit">
                Submit
            </button>
        </div>
    </div>
</x-admin.section.card>
