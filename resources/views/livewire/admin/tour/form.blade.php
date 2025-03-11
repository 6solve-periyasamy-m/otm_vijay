@php
$atol = [
    null => "Match System (Currently: " . (flag('atol.enabled', true) ? 'Enabled' : 'Disabled') . ")",
    0 => "Disabled",
    1 => "Enabled",
];
@endphp

<div class="row">
    <div class="col-xl-12">
        <x-admin.section.card>
            <div class="row">
                <x-livewire.input.select.event.normal name="tour.event_id" value="{{ $tour?->event_id }}" label="Associated Event" />
                <x-livewire.input wire:model="tour.name" width="8" label="Name" required />
                <x-livewire.input.select.brand name="tour.brand_id" label="Brand" value="{{ $tour?->brand_id }}" width="2" />
                <x-livewire.input.select.tax-bracket name="tour.tax_bracket_id" label="Tax Bracket" value="{{ $tour?->tax_bracket_id }}" width="2" />
                <x-livewire.input wire:model="tour.description" label="Description" />
                <x-livewire.input.dropdown wire:model="tour.atol_protected" :items="$atol" width="6" label="ATOL Protection" />
                <x-livewire.input.select.tour-category name="tour.tour_category_id" width="6" label="Tour Category" clear />
                <x-livewire.input wire:model="tour.booking_form_url" width="10" label="Booking Form URL" />
                <x-livewire.input.checkbox wire:model="tour.is_active" width="2" label="Is Active?" />
                <x-livewire.input wire:model="tour.city" label="City" width="6" />
                <x-livewire.input.select2 name="tour.country_id" value="{{ $tour->country_id }}" route="countries" width="6" label="Country" />
            </div>
        </x-admin.section.card>
    </div>
    <div class="col-xl-4">
        <x-admin.section.card>
            <x-livewire.input type="date" wire:model="tour.final_payment" label="Final Payment Date" required />
            <x-livewire.input type="date" wire:model="tour.date_from" label="Tour Start" required />
            <x-livewire.input type="date" wire:model="tour.date_to" label="Tour End" required />
        </x-admin.section.card>
    </div>
    <div class="col-xl-4">
        <x-admin.section.card>
            <div class="row">
                <x-livewire.input wire:model="tour.base_price_per_person" label="Price per Person" required type="number" step="0.01" />
                <x-livewire.input wire:model="tour.deposit" label="Deposit" width="6" type="number" step="0.01" />
                <x-livewire.input.checkbox wire:model="tour.is_deposit_percentage" label="Percentage?" width="6" />
                <x-livewire.input wire:model="tour.booking_fee" label="Booking Fee" width="6" />
                <x-livewire.input wire:model="tour.single_occupancy_surcharge" label="Single Occupancy Surcharge" width="6" />
            </div>
        </x-admin.section.card>
    </div>
    <div class="col-xl-4">
        <x-admin.section.card>
            <div class="row">
                <x-livewire.input wire:model="tour.stock" label="Stock" />
                <span class="fw-bold">Stock Control (Warning: Updating this does not update existing components)</span>
                <x-livewire.input.checkbox wire:model="tour.stock_control_active" label="Tour" width="6" />
                <x-livewire.input.checkbox wire:model="tour.accommodation_stock_control" label="Accommodation" width="6" />
                <x-livewire.input.checkbox wire:model="tour.activity_stock_control" label="Activity" width="6" />
                <x-livewire.input.checkbox wire:model="tour.flight_stock_control" label="Flight" width="6" />
                <x-livewire.input.checkbox wire:model="tour.transport_stock_control" label="Transport" width="6" />
                <x-livewire.input.checkbox wire:model="tour.merchandise_stock_control" label="Merchandise" width="6" />
            </div>
        </x-admin.section.card>
    </div>
    <div class="col-xl-12">
        <x-admin.section.card>
            <x-livewire.input.text-area wire:model="tour.notes" label="Tour Notes" />
        </x-admin.section.card>
    </div>
    <div class="col-xl-6">
        <x-admin.section.card>
            <x-livewire.input.select.large-text-template name="termsTemplate" label="Copy from Template" value="{{ $termsTemplate }}" />
            <x-livewire.ckeditor name="tour.terms" value="{{ $tour?->terms }}" label="Terms and Conditions" required />
        </x-admin.section.card>
    </div>
    <div class="col-xl-6">
        <x-admin.section.card>
            <x-livewire.input.select.large-text-template name="footerTemplate" label="Copy from Template" value="{{ $footerTemplate }}" />
            <x-livewire.ckeditor name="tour.invoice_footer" value="{{ $tour?->invoice_footer }}" label="Invoice Footer" />
        </x-admin.section.card>
    </div>
    <div class="col-xl-12">
        <x-admin.section.card>
            <button class="btn btn-success" wire:click="save">Save Tour</button>
        </x-admin.section.card>
    </div>
</div>
