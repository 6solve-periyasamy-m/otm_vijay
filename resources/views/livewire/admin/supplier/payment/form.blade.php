<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h4 class="fw-bold">{{ __($information?->id !== null ? 'supplier.payment.form.update' : 'supplier.payment.form.create') }}</h4>
        </div>
        <div class="row">
            <x-livewire.input.select2 name="information.bank_id" required label="{{__('supplier.payment.form.fields.bank')}}" route="banks" create="openModal('admin.system.bank.form');" />
            <x-livewire.input wire:model="information.account_number" width="6" required label="{{__('supplier.payment.form.fields.account_number')}}" />
            <x-livewire.input wire:model="information.sort_code" width="6" label="{{__('supplier.payment.form.fields.sort_code')}}" />
            <x-livewire.input wire:model="information.bic_swift_code" width="6" label="{{__('supplier.payment.form.fields.bic_swift_code')}}" />
            <x-livewire.input wire:model="information.iban" width="6" label="{{__('supplier.payment.form.fields.iban')}}" />
            <x-livewire.input wire:model="information.notes" label="{{__('supplier.payment.form.fields.notes')}}" />
            <button class="btn btn-primary" wire:click="save">Submit</button>
        </div>
    </div>
</div>

