<div class="otm-callout">
    <div class="row">
        <div class="col-4">
            <p>{{ __('supplier.details.name') }}</p>
            <h6 class="fw-bold">{{ $contract->supplier->name }}</h6>
        </div>
        <div class="col-4">
            <p>{{ __('supplier.contract.details.number') }}</p>
            <h6 class="fw-bold">{{ $contract->purchase_order_number }}</h6>
        </div>
        <div class="col-4">
            <p>{{ __('supplier.contract.details.supplier_reference') }}</p>
            <h6 class="fw-bold">{{ $contract->reference_number }}</h6>
        </div>

        <div class="col-xl-2 col-lg-2 col-6">
            <p>{{ __('supplier.contract.details.currency') }}</p>
            <h6 class="fw-bold">
                @isset($contract->currency)
                    {{ $contract->currency->name }} ({{ $contract->currency->code }})
                @else
                    {{ __('supplier.details.currency.none') }}
                @endisset
            </h6>
        </div>
        <div class="col-xl-2 col-lg-2 col-6">
            <p>{{ __('supplier.details.exchange.name') }}</p>
            <h6 class="fw-bold">{{ ($contract->agreed_exchange ?? 0) > 0 ? $contract->agreed_exchange : __('supplier.details.exchange.none') }}</h6>
        </div>
        <div class="col-xl-2 col-lg-2 col-6">
            <p>{{ __('supplier.contract.details.tax') }}</p>
            <h6 class="fw-bold">{{ $contract->tax_rate }}%</h6>
        </div>
        <div class="col-xl-2 col-lg-2 col-6">
            <p>{{ __('supplier.contract.details.total') }}</p>
            <h6 class="fw-bold">{{ f_currency($contract->total_cost, $contract->currency->code) }} ({{ f_currency($contract->local_cost) }})</h6>
        </div>
        <div class="col-xl-2 col-lg-2 col-6">
            <p>Quantity of Components</p>
            <h6 class="fw-bold">{{ $contract->component_quantity }}</h6>
        </div>
        <div class="col-xl-2 col-lg-2 col-6">
            <p>Total Cost of Components</p>
            <h6 class="fw-bold">{{ f_currency($contract->component_cost, $contract->currency->code) }}</h6>
        </div>
        <div class="col-xl-12">
            <p>{{ __('supplier.contract.details.notes') }}</p>
            <h6 class="fw-bold">{{ $contract->notes }}</h6>
        </div>
        <div class="col-12">
            <a class="btn btn-info" href="{{ route('supplier.link', ['supplier' => $contract->supplier, 'contract' => $contract,]) }}">
                {{ Icon::link() }}
                <span>{{ __('supplier.contract.details.buttons.link') }}</span>
            </a>
            <button class="btn btn-success" wire:click="$emit('openModal', 'admin.supplier.contract.form', {'supplier': {{$contract->supplier_id}}, 'contract': {{$contract->id}}})">
                {{ Icon::edit() }}
                <span>{{ __('supplier.contract.details.buttons.edit') }}</span>
            </button>
            <button class="btn btn-danger" wire:click="delete">
                {{ Icon::delete() }}
                <span>{{ __('supplier.contract.details.buttons.delete') }}</span>
            </button>
        </div>
    </div>
</div>

