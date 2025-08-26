<x-admin.section.card>
    <div class="row">
        <x-livewire.input.select.accommodation.room-type name="inventory.room_type_id" label="Room Type" value="{{ $inventory?->room_type_id }}" width="2" />
        <x-livewire.input.select.accommodation.board-type name="inventory.board_type_id" label="Board Type" value="{{ $inventory?->board_type_id }}" width="2" />
        <x-livewire.input.select.accommodation.room-category name="inventory.room_category_id" label="Category" value="{{ $inventory?->room_category_id }}" width="2" clear />
        <div class="col-xl-12 mb-3">
            <x-livewire.ckeditor name="inventory.category_description" value="{{ $inventory?->category_description }}" label="Description" />
        </div>
        <x-livewire.input.select.accommodation-inventory width="6" label="Stock Parent" name="inventory.stock_parent_id" value="{{ $inventory?->stock_parent_id }}" clear />
        <x-livewire.input type="datetime-local" wire:model="inventory.check_in" wire:change="updatedInventoryCheckIn" width="5" label="Check In" id="check_in" min="{{ now()->format('Y-m-d\TH:i') }}"/>
        <x-livewire.input.checkbox wire:model="inventory.check_in_time_confirmed" width="1" label="Confirmed?" />
        <x-livewire.input type="datetime-local" wire:model="inventory.check_out" width="5" label="Check Out" id="check_out" :min="$minEndDate"/>
        <x-livewire.input.checkbox wire:model="inventory.check_out_time_confirmed" width="1" label="Confirmed?" />
        <x-livewire.input wire:model="inventory.stock" width="11" label="Stock" />
        <x-livewire.input.checkbox wire:model="inventory.fit_selectable" width="1" label="FIT Selectable" />
        <x-livewire.input wire:model="inventory.purchase_price" width="3" label="Purchase Price" type="number" step="0.01" />
        <x-livewire.input.select.currency name="currency_id" width="3" label="Currency Override" value="{{ $inventory->currency_id }}" clearable />
        <x-livewire.input wire:model="inventory.sales_price" width="6" label="Sales Price" type="number" step="0.01" />
        <x-livewire.input.text-area wire:model="inventory.internal_notes" width="6" label="Internal Notes" />
        <x-livewire.input.text-area wire:model="inventory.external_notes" width="6" label="External Notes" />
        <div>
            <button class="btn btn-primary" wire:click="save">
                Submit
            </button>
        </div>
    </div>
</x-admin.section.card>