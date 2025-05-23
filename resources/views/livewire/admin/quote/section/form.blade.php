<div>
    <x-admin.section.card>
        <div class="float-end">
            <button wire:click="save" class="btn btn-success">{{ Icon::save() }} Save Section</button>
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <div class="row">
            <x-livewire.input wire:model="section.title" label="Name" width="8" />
            <x-livewire.input.select.quote.quote-section-type name="section.quote_section_type_id" value="{{ $section->quote_section_type_id }}" width="4" label="Type" createForm="admin.quote.section.type.form" />
            <x-livewire.input.select.large-text-template name="bodyTemplate" label="Copy from Template" value="{{ $bodyTemplate }}" />
            <x-livewire.ckeditor name="section.body" value="{{ $section?->body }}" label="Body" />
            <x-livewire.input type="file" width="3" wire:model="image" label="Image" />
            <x-livewire.input type="number" width="3" wire:model="section.order" label="Order" />
            <x-livewire.input type="datetime-local" width="3" wire:model="section.sort_date" label="Sort Date" />
            <x-livewire.input.checkbox wire:model="section.hidden" label="Hide on document?" width="3" />
            <x-livewire.input.select.currency name="section.currency_id" value="{{ $section?->currency_id }}" label="Currency" width="4" />
            <x-livewire.input wire:model="section.purchase_price" label="Purchase Price Per Unit" width="4" />
            <x-livewire.input wire:model="section.quantity" label="Quantity" width="4" />
        </div>
    </x-admin.section.card>
</div>
