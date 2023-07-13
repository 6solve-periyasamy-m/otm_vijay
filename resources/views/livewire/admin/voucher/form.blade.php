<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h4 class="fw-bold">{{ __($voucher?->id !== null ? 'voucher.form.edit' : 'voucher.form.create') }}</h4>
        </div>
        <div class="row">
            <x-livewire.input wire:model="voucher.code" required width="2" label="{{__('voucher.form.fields.code')}}" />
            <x-livewire.input wire:model="voucher.name" required width="8" label="{{__('voucher.form.fields.name')}}" />
            <x-livewire.input type="date" wire:model="voucher.expiry" required width="2" label="{{__('voucher.form.fields.expiry')}}" />
            <x-livewire.input wire:model="voucher.description" label="{{__('voucher.form.fields.description')}}" />
            <x-livewire.input wire:model="voucher.limit" width="4" label="Voucher Usage Limit (0 for unlimited)" />
            <x-livewire.input.checkbox wire:model="voucher.active" width="4" label="{{__('voucher.form.fields.active')}}" />
            <x-livewire.input.checkbox wire:model="voucher.global" width="4" label="{{__('voucher.form.fields.global')}}" />
            <button class="btn btn-primary" wire:click="save">Submit</button>
        </div>
    </div>
</div>
