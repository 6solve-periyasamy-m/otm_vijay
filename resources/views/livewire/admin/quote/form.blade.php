<div>
    <x-admin.section.card>
        <div class="float-end">
            <button wire:click="save" class="btn btn-success">{{ Icon::save() }} Save Quote</button>
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <div class="row">
            <x-livewire.input wire:model="quote.name" label="Name" width="3" required />
            <x-livewire.input.select.brand name="quote.brand_id" value="{{$quote->brand_id}}" label="Branding" width="3" />
            <x-livewire.input.select.tax-bracket name="quote.tax_bracket_id" value="{{$quote->tax_bracket_id}}" label="Tax Bracket" width="3" />
            <x-livewire.input.select.user name="quote.consultant_id" value="{{$quote->consultant_id ?? get_current_admin()?->id}}" label="Consultant" width="3" />
            <!-- -->
            <x-livewire.input.select.event.normal name="quote.event_id" value="{{$quote->event_id}}" label="Event" width="3" />
            <x-livewire.input.select.organization name="quote.organization_id" value="{{$quote->organization_id}}" label="Organization" width="3" wire:change="updatedQuoteOrganizationId($event.target.value)"/>
            <x-livewire.input wire:model="quote.commission" label="Commission (%)" width="2" />
            <x-livewire.input.checkbox wire:model="agentRequired" label="Require Agent?" width="1" />
            <x-livewire.input.select.agent name="quote.agent_id" value="{{$quote->agent_id}}" label="Agent" width="3" />
            <!-- -->
            <x-livewire.input.select.currency name="quote.currency_id" value="{{$quote->currency_id}}" label="Currency" width="3" clear />
            <x-livewire.input wire:model="quote.deposit" label="Deposit" width="2"  />
            <x-livewire.input.checkbox wire:model="quote.is_deposit_percentage" label="Percentage?" width="1" />
            <x-livewire.input key="price" wire:model.defer.300ms="price" label="Base Price" width="3" required  />
            <x-livewire.input wire:model="quote.single_occupancy_surcharge" label="Single Occupancy Surcharge" width="3" required />
            <!-- -->
            <x-livewire.input.text-area wire:model="quote.description" label="Description" />
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <div class="row">
            <x-livewire.input.select.customer name="prospect.customer_id" value="{{ $prospect->customer_id }}" label="Lead Booker" width="4" required />
            <x-livewire.input.checkbox wire:model="prospect.travelling" label="Lead Travelling" width="4" />
            <x-livewire.input.checkbox wire:model="prospect.paying" label="Lead Paying" width="4" />
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <div class="row">
            <x-livewire.input type="date" wire:model="quote.date_from" wire:change="updatedQuoteDateFrom" label="Date From" width="3" required id="date_from"  min="{{ now()->format('Y-m-d') }}" />
            <x-livewire.input type="date" wire:model="quote.date_to" label="Date To" width="3" required id="date_to" :min="$minToDate"  />
            <x-livewire.input type="date" wire:model="quote.final_payment" label="Final Payment" width="3" required id="final_payment"  :max="$maxFinalDate"  />
            <x-livewire.input type="date" wire:model="quote.expires" label="Quote Expiry Date" width="3" required min="{{ now()->format('Y-m-d') }}" />
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
        @foreach($costs as $key => $cost)
            <div class="col-xl-4">
                <x-admin.section.card>
                    <div class="row">
                        <x-livewire.input wire:model="costs.{{$key}}.name" label="Name" width="3" />
                        <x-livewire.input wire:model="costs.{{$key}}.amount" label="Amount" width="3" />
                        <x-livewire.input.checkbox wire:model="costs.{{$key}}.per_customer" label="Per Customer" width="3" />
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
            <x-livewire.input.text-area wire:model="quote.internal_notes" label="Internal Notes" width="6" />
            <x-livewire.input.text-area wire:model="quote.external_notes" label="External Notes" width="6" />
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <div class="row">
            <div class="col-xl-4">
                <x-livewire.input.select.large-text-template name="footerTemplate" label="Copy from Template" value="{{ $footerTemplate }}" />
                <x-livewire.ckeditor name="quote.invoice_footer" value="{{ $quote?->invoice_footer }}" label="Invoice Footer" />
            </div>
            <div class="col-xl-4">
                <x-livewire.input.select.large-text-template name="paymentTemplate" label="Copy from Template" value="{{ $paymentTemplate }}" />
                <x-livewire.ckeditor name="quote.payment_details" value="{{ $quote?->payment_details }}" label="Payment Details" />
            </div>
            <div class="col-xl-4">
                <x-livewire.input.select.large-text-template name="termsTemplate" label="Copy from Template" value="{{ $termsTemplate }}" />
                <x-livewire.ckeditor name="quote.terms" value="{{ $quote?->terms }}" label="Terms and Conditions" required />
            </div>
        </div>
    </x-admin.section.card>
</div>
