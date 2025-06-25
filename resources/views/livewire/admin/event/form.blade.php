<div class="row">
    <div class="col-xl-12">
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
                <x-livewire.input.select.event.main name="event.parent_event_id" label="Parent Event" value="{{ $event?->parent_event_id }}" width="2" />
                <x-livewire.input.dropdown wire:model="event.event_category" label="Event Category" :items="\App\Models\Helper\Enum\EventType::toArray()" width="2" />
                <x-livewire.input wire:model="event.description" label="Description" value="{{ $event?->description }}" />
                <x-livewire.input type="date" wire:model="event.starts_at" wire:change="updatedEventStartAt" label="Starts At" required width="4" id="start_at"  min="{{ now()->format('Y-m-d') }}" />
                <x-livewire.input type="date" wire:model="event.ends_at" label="Ends At" width="4" required id="ends_at" :min="$minEndDate"/>
                <x-livewire.input type="file" wire:model="image" label="Image" width="2" />
                <x-livewire.input type="file" wire:model="banner" label="Banner" width="2" />
                <x-livewire.input wire:model="event.booking_url" label="Booking URL" />
                <x-livewire.input.text-area wire:model="event.notes" label="Notes" />
            </div>
        </x-admin.section.card>
    </div>
    <div class="col-xl-4">
        <x-admin.section.card>
            <x-livewire.input.select.large-text-template name="termsTemplate" label="Copy from Template" value="{{ $termsTemplate }}" :filterType="\App\Models\Helper\Enum\LargeTextType::TERMS->value" />
            <x-livewire.ckeditor name="event.final_terms" value="{{ $event?->final_terms }}" label="Final Terms and Conditions" />
        </x-admin.section.card>
    </div>
    <div class="col-4">
        <x-admin.section.card>
            <x-livewire.input wire:model="event.itinerary_email_subject" label="Itinerary Subject" />
            <x-livewire.input.select.large-text-template name="emailTemplate" label="Copy from Template" value="{{ $termsTemplate }}" />
            <x-livewire.ckeditor name="event.itinerary_email_template" value="{{ $event?->itinerary_email_template }}" label="Itinerary Body" />
        </x-admin.section.card>
    </div>
    <div class="col-xl-4">
        <x-admin.section.card>
            <div class="row">
                <x-livewire.input.select.user name="user" label="User to Prefill" />
                <x-livewire.input wire:model="event.onsite_name" label="Onsite Name" />
                <x-livewire.input wire:model="event.onsite_email" label="Onsite Email" />
                <x-livewire.input wire:model="event.onsite_phone" label="Onsite Phone" />
            </div>
        </x-admin.section.card>
    </div>
</div>
