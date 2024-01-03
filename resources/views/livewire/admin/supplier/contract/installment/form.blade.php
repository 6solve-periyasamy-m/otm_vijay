<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h4 class="fw-bold">{{ __($installment?->id !== null ? 'supplier.contract.installment.form.title.update' : 'supplier.contract.installment.form.title.create') }}</h4>
        </div>
        <div class="row">
            <x-livewire.input wire:model.defer.500ms="installment.amount" required width="6" label="{{__('supplier.contract.installment.form.fields.amount')}}" />
            <x-livewire.input type="date" wire:model.defer.500ms="installment.due" required width="6" label="{{__('supplier.contract.installment.form.fields.due')}}" />
            <button class="btn btn-primary" wire:click="save">Submit</button>
        </div>
    </div>
</div>