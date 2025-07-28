<x-admin.section.card>
    <div class="row">
        <x-livewire.input.checkbox wire:model="toDate" label="Move to date?" width="3" />
        @if($toDate)
            <x-livewire.input type="date" wire:model="date" width="3" />
        @else
            <x-livewire.input type="date" wire:model="date" width="3" disabled />
        @endif
        <div class="col-xl-3">
            Moving the tour to a new date will attempt to match inventory rows, and if it cannot, will create copies of the inventory rows with the dates shifted.
        </div>
        <div class="col-xl-3">
            <button wire:click="save" class="btn btn-success">
                {{ Icon::copy() }} Confirm Duplication
            </button>
        </div>
    </div>
</x-admin.section.card>