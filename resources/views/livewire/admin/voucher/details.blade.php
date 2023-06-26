<div class="otm-callout">
    <div class="row">
        <div class="col-4 col-xl-2">
            <p>{{ __('voucher.details.code') }}</p>
            <h6 class="fw-bold">{{ $voucher->code }}</h6>
        </div>
        <div class="col-8 col-xl-10">
            <p>{{ __('voucher.details.name') }}</p>
            <h6 class="fw-bold">{{ $voucher->name }}</h6>
        </div>
        <div class="col-2">
            <p>{{ __('voucher.details.active') }}</p>
            <h6 class="fw-bold">{{ f_bool($voucher->active) }}</h6>
        </div>
        <div class="col-2">
            <p>{{ __('voucher.details.global') }}</p>
            <h6 class="fw-bold">{{ f_bool($voucher->global) }}</h6>
        </div>
        <div class="col-12">
            <p>{{ __('voucher.details.description') }}</p>
            <h6 class="fw-bold">{{ $voucher->description }}</h6>
        </div>
        <div class="col-12">
            <button class="btn btn-success" wire:click="$emit('openModal', 'admin.voucher.form', {'voucher': {{$voucher->id}}})">
                {{ Icon::edit() }}
                <span>{{ __('voucher.details.buttons.edit') }}</span>
            </button>
            <button class="btn btn-danger" wire:click="destroy">
                {{ Icon::delete() }}
                <span>{{ __('voucher.details.buttons.delete') }}</span>
            </button>
        </div>
    </div>
</div>
