<div>
    <x-admin.section.card>
        <div class="float-end">
            <button wire:click="save" class="btn btn-success">Save</button>
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <div class="row">
            <x-livewire.input.select.brand name="quote.brand" value="{{ $quote->brand }}" label="Branding" width="3" />
            <x-livewire.input type="date" wire:model="quote.expiry" label="Expiry" width="3" />
            <x-livewire.input.select.customer name="quote.lead" value="{{ $quote->lead }}" label="Lead Traveller" width="3" />
            <div class="col-3 row">
                <x-livewire.input.checkbox wire:model="quote.travelling" label="Lead Travelling?" width="6" />
                <x-livewire.input.checkbox wire:model="quote.paying" label="Lead Paying?" width="6" />
            </div>
            <x-livewire.input wire:model="quote.singleOccupancy" label="Single Occupancy Surcharge" width="3" />
            <x-livewire.input.select.tax-bracket name="quote.tax" value="{{$quote->tax}}" label="Tax Bracket" width="3" />
            <x-livewire.input type="date" wire:model="quote.final" label="Final Payment" width="2" />
            <x-livewire.input wire:model="quote.deposit" label="Deposit" width="2" />
            <x-livewire.input.checkbox wire:model="quote.depositPercentage" label="Percentage?" width="2" />
            <x-livewire.input.select.organization name="quote.organization" value="{{$quote->organization}}" width="5" />
            <x-livewire.input wire:model="quote.commission" label="Commission (%)" width="2" />
            <x-livewire.input.select.agent name="quote.agent" value="{{$quote->agent}}" width="5" />
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <div class="row">
            <x-livewire.input.text-area wire:model="quote.internalNotes" label="Internal Notes" width="6" />
            <x-livewire.input.text-area wire:model="quote.externalNotes" label="External Notes" width="6" />
        </div>
    </x-admin.section.card>
</div>
