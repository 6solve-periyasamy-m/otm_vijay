<x-admin.section.card>
    <div class="row">
        <x-livewire.input.select.accommodation.room-type name="inventory.room_type_id" label="Room Type" value="{{ $inventory?->room_type_id }}" width="2" />
        <x-livewire.input.select.accommodation.board-type name="inventory.board_type_id" label="Board Type" value="{{ $inventory?->board_type_id }}" width="2" />
        <x-livewire.input.select.accommodation.room-category name="inventory.room_category_id" label="Category" value="{{ $inventory?->room_category_id }}" width="2" clear />
        <x-livewire.input.select.accommodation-inventory width="6" label="Stock Parent" name="inventory.stock_parent_id" value="{{ $inventory?->stock_parent_id }}" clear />
        <x-livewire.input type="datetime-local" wire:model="inventory.check_in" width="5" label="Check In" />
        <x-livewire.input.checkbox wire:model="inventory.check_in_time_confirmed" width="1" label="Confirmed?" />
        <x-livewire.input type="datetime-local" wire:model="inventory.check_out" width="5" label="Check Out" />
        <x-livewire.input.checkbox wire:model="inventory.check_out_time_confirmed" width="1" label="Confirmed?" />
        <x-livewire.input wire:model="inventory.stock" width="11" label="Stock" />
        <x-livewire.input.checkbox wire:model="inventory.fit_selectable" width="1" label="FIT Selectable" />
        <x-livewire.input wire:model="inventory.purchase_price" width="6" label="Purchase Price" />
        <x-livewire.input wire:model="inventory.sales_price" width="6" label="Sales Price" />
        <x-livewire.input.text-area wire:model="inventory.internal_notes" width="6" label="Internal Notes" />
        <x-livewire.input.text-area wire:model="inventory.external_notes" width="6" label="External Notes" />
        <div>
            <button class="btn btn-primary" wire:click="save">
                Submit
            </button>
        </div>
    </div>
</x-admin.section.card>