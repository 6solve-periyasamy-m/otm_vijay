<div>
    <x-admin.section.card>
        <div class="row">
            <div class="row">
                <x-livewire.input.select.customer name="customer" value="{{ $customer }}" clear label="Customer" width="5" />
                <div class="col-2 flex justify-center content-center">
                    OR
                </div>
                <x-livewire.input.select.agent name="agent" value="{{$agent}}" clear label="Agent" width="5" />
            </div>
            <x-livewire.input.select.order.payment-method name="payment.payment_method_id" value="{{ $payment?->payment_method_id }}" label="Payment Method" required width="4" />
            <x-livewire.input wire:model="payment.paid_on" type="datetime-local" label="Paid On" required width="4" />
            <x-livewire.input wire:model="payment.payment_fee" label="Payment Fee" width="4" type="number" step="0.01" />
            <x-livewire.input wire:model="payment.amount" label="Amount" required width="9" type="number" step="0.01" />
            <div class="col-xl-3 flex justify-between content-center">
                <div class="my-auto">
                    <button wire:click="deposit" class="btn btn-warning">Deposit</button>
                </div>
                <div class="my-auto">
                    <button wire:click="next" class="btn btn-warning">Next Installment</button>
                </div>
                <div class="my-auto">
                    <button wire:click="remaining" class="btn btn-warning">Remaining</button>
                </div>
            </div>
            <x-livewire.input.text-area wire:model="payment.internal_notes" label="Internal Notes" width="12" />
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <button class="btn btn-success" wire:click="save">Save Payment</button>
    </x-admin.section.card>
</div>
