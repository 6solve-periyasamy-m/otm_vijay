<div class="otm-callout">
    <div class="row">
        <div class="col-6">
            <p>{{ __('supplier.details.name') }}</p>
            <h6 class="fw-bold">{{ $contract->supplier->name }}</h6>
        </div>
        <div class="col-6">
            <p>{{ __('supplier.contract.details.number') }}</p>
            <h6 class="fw-bold">{{ $contract->purchase_order_number }}</h6>
        </div>

        <div class="col-xl-3 col-lg-3 col-6">
            <p>{{ __('supplier.contract.details.currency') }}</p>
            <h6 class="fw-bold">
                @isset($contract->currency)
                    {{ $contract->currency->name }} ({{ $contract->currency->code }})
                @else
                    {{ __('supplier.details.currency.none') }}
                @endisset
            </h6>
        </div>
        <div class="col-xl-3 col-lg-3 col-6">
            <p>{{ __('supplier.details.exchange.name') }}</p>
            <h6 class="fw-bold">{{ ($contract->agreed_exchange ?? 0) > 0 ? $contract->agreed_exchange : __('supplier.details.exchange.none') }}</h6>
        </div>
        <div class="col-xl-3 col-lg-3 col-6">
            <p>{{ __('supplier.contract.details.total') }}</p>
            <h6 class="fw-bold">{{ f_currency($contract->total_cost) }}</h6>
        </div>
        <div class="col-xl-3 col-lg-3 col-6">
            <p>{{ __('supplier.contract.details.per_item') }}</p>
            <h6 class="fw-bold">{{ f_currency($contract->price_per_item) }}</h6>
        </div>
        <div class="col-12">
            <button class="btn btn-success" wire:click="$emit('openModal', 'admin.supplier.contract.form', {'supplier': {{$contract->supplier_id}}, 'contract': {{$contract->id}})">
                {{ Icon::edit() }}
                <span>{{ __('supplier.contract.details.buttons.edit') }}</span>
            </button>
            <button class="btn btn-danger" wire:click="delete`1">
                {{ Icon::delete() }}
                <span>{{ __('supplier.details.buttons.delete') }}</span>
            </button>
        </div>
    </div>
</div>

