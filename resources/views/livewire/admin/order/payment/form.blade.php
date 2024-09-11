<div>
    <x-admin.section.card>
        <div class="row">
            <x-livewire.input.select.customer name="payment.customer_id" value="{{ $payment?->customer_id }}" label="Customer" required width="4" />
            <x-livewire.input.select.order.payment-method name="payment.payment_method_id" value="{{ $payment?->payment_method_id }}" label="Payment Method" required width="4" />
            <x-livewire.input wire:model="payment.paid_on" type="datetime-local" label="Paid On" required width="4" />
            <x-livewire.input wire:model="payment.amount" label="Amount" required width="9" />
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
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <button class="btn btn-success" wire:click="save">Save Payment</button>
    </x-admin.section.card>
</div>
