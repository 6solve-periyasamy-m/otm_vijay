<div class="otm-callout">
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-6">
            <p>{{ __('supplier.details.name') }}</p>
            <h6 class="fw-bold">{{ $supplier->name }}</h6>
        </div>
        <div class="col-xl-3 col-lg-3 col-6">
            <p>{{ __('supplier.details.contact.website') }}</p>
            <h6 class="fw-bold"><a href="{{ $supplier->website }}">{{ $supplier->website }}</a></h6>
        </div>
        <div class="col-xl-3 col-lg-3 col-6">
            <p>{{ __('supplier.details.contact.telephone') }}</p>
            <h6 class="fw-bold"><a href="tel:{{ $supplier->telephone }}">{{ $supplier->telephone }}</a></h6>
        </div>
        <div class="col-xl-3 col-lg-3 col-6">
            <p>{{ __('supplier.details.contact.email') }}</p>
            <h6 class="fw-bold"><a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a></h6>
        </div>
        <div class="col-xl-6 col-lg-6 col-12">
            <p>{{ __('supplier.details.contact.address') }}</p>
            <h6 class="fw-bold">{{ $supplier->address }}</h6>
        </div>
        <div class="col-xl-3 col-lg-3 col-6">
            <p>{{ __('supplier.details.currency.name') }}</p>
            <h6 class="fw-bold">
                @isset($supplier->currency)
                    {{ $supplier->currency->name }} ({{ $supplier->currency->code }})
                @else
                    {{ __('supplier.details.currency.none') }}
                @endisset
            </h6>
        </div>
        <div class="col-xl-3 col-lg-3 col-6">
            <p>{{ __('supplier.details.exchange.name') }}</p>
            <h6 class="fw-bold">{{ ($supplier->agreed_exchange ?? 0) > 0 ? $supplier->agreed_exchange : __('supplier.details.exchange.none') }}</h6>
        </div>
        <div class="col-2">
            <p>{{ __('supplier.details.notes') }}</p>
            <h6 class="fw-bold">{{ $supplier->notes }}</h6>
        </div>
        <div class="col-12">
            <button class="btn btn-success" wire:click="$emit('openModal', 'admin.supplier.form', {'supplier': {{$supplier->id}}})">
                {{ Icon::edit() }}
                <span>{{ __('supplier.details.buttons.edit') }}</span>
            </button>
            <button class="btn btn-danger" wire:click="destroy">
                {{ Icon::delete() }}
                <span>{{ __('supplier.details.buttons.delete') }}</span>
            </button>
        </div>
    </div>
</div>

