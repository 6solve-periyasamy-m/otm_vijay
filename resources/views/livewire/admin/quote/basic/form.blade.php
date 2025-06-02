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
            <x-livewire.input wire:model="quote.singleOccupancy" label="Single Occupancy Surcharge" width="2" />
            <x-livewire.input.select.tax-bracket name="quote.tax" value="{{$quote->tax}}" label="Tax Bracket" width="2" />
            <x-livewire.input.select.currency name="quote.currency" value="{{$quote->currency}}" label="Currency" width="2" />
            <x-livewire.input type="date" wire:model="quote.final" label="Final Payment" width="2" />
            <x-livewire.input wire:model="quote.deposit" label="Deposit" width="2" />
            <x-livewire.input.checkbox wire:model="quote.depositPercentage" label="Percentage?" width="2" />
            <x-livewire.input.select.organization name="quote.organization" label="Organisation" value="{{$quote->organization}}" width="5" />
            <x-livewire.input wire:model="quote.commission" label="Commission (%)" width="2" />
            <x-livewire.input.select.agent name="quote.agent" label="Agent" value="{{$quote->agent}}" width="5" />
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <div class="d-flex justify-content-between">
            <div>
                <h4 class="fw-bold">
                    Additional Costs
                </h4>
            </div>
            <div>
                <button wire:click="addCost" class="btn btn-success">{{ Icon::plus() }} Add Cost</button>
            </div>
        </div>
    </x-admin.section.card>
    <div class="row">
        @foreach($quote->costs as $key => $cost)
            <div class="col-xl-4">
                <x-admin.section.card>
                    <div class="row">
                        <x-livewire.input wire:model="quote.costs.{{$key}}.name" label="Name" width="3" />
                        <x-livewire.input wire:model="quote.costs.{{$key}}.amount" label="Amount" width="3" />
                        <x-livewire.input.checkbox wire:model="quote.costs.{{$key}}.per_customer" label="Per Customer" width="3" />
                        <div class="col-3">
                            <button class="btn btn-danger" wire:click="removeCost({{$key}})" title="Remove">{{Icon::trash()}}</button>
                        </div>
                    </div>
                </x-admin.section.card>
            </div>
        @endforeach
    </div>
    <x-admin.section.card>
        <div class="row">
            <x-livewire.input.text-area wire:model="quote.internalNotes" label="Internal Notes" width="6" />
            <x-livewire.input.text-area wire:model="quote.externalNotes" label="External Notes" width="6" />
        </div>
    </x-admin.section.card>
</div>
