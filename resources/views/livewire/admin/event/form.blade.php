<div>
    <x-admin.section.card>
        <div class="float-end">
            <button wire:click="save" class="btn btn-success">{{ Icon::save() }} Save Event</button>
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <div class="row">
            <x-livewire.input wire:model="event.name" label="Name" width="4" />
            <x-livewire.input.select.tax-bracket name="event.tax_bracket_id" label="Tax Bracket" value="{{ $event?->tax_bracket_id }}" width="2"/>
            <x-livewire.input.select.brand name="event.brand_id" label="Brand" value="{{ $event?->brand_id }}" width="2" />
            <x-livewire.input.select.event.main name="event.parent_id" label="Parent Event" value="{{ $event?->parent_event_id }}" width="2" />
            <x-livewire.input.dropdown wire:model="event.event_category" label="Event Category" :items="\App\Models\Helper\Enum\EventType::toArray()" width="2" />
            <x-livewire.input wire:model="event.description" label="Description" value="{{ $event?->description }}" />
            <x-livewire.input type="date" wire:model="event.starts_at" label="Starts At" width="5" />
            <x-livewire.input type="date" wire:model="event.ends_at" label="Ends At" width="5" />
            <x-livewire.input type="file" wire:model="image" label="Image" width="2" />
            <x-livewire.input wire:model="event.booking_url" label="Booking URL" />
            <x-livewire.input.text-area wire:model="event.notes" label="Notes" />
        </div>
    </x-admin.section.card>
</div>
