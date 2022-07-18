@extends('layout.master')

@php /** @var \App\Models\Merchandise\Merchandise $merchandise */ @endphp

@push('header-stack')
    <style>
        .image {
            background-image: url('{{ asset(setting('company.logo')) }}');
            background-repeat: no-repeat;
        }
        .icon {
            max-width: 200px;
            max-height: 200px;
            background-size: 200px;
        }
        .thumbnail {
            max-width: 150px;
            max-height: 150px;
            background-size: 150px;
        }
        .item {
            margin: 5px;
        }
        .inventory-container {
            display: flex;
            flex-wrap: wrap;
        }
        .inventory {
            display: flex;
            flex-direction: column;
            width: max-content;
            margin: 5px 10px;
        }
        .vertical-divider {
            width: 1px;
            border-right: 5px solid #cccccc;
            border-radius: 2px;
        }
    </style>
    <script>
        function update(selector) {
            selector = $(selector);
            let selected = selector.find(':selected');
            let box = selector.closest('.inventory');
            console.warn(selected.attr('purchase'));
            box.find('.purchase').text(selected.attr('purchase'));
            box.find('.sales').text(selected.attr('sales'));
            box.find('.stock').text(selected.attr('stock'));
            box.find('.used').text(selected.attr('used'));
            box.find('.available').text(selected.attr('available'));
            box.find('.edit-btn').attr('href', selected.attr('edit'));
            box.find('.delete-btn').attr('href', selected.attr('delete'));
        }
    </script>
@endpush

@section('content')
    <div class="otm-callout">
        <div class="row">
            <div class="col-xl-2">
                <img src="{{ $merchandise->asset }}" class="image icon">
            </div>
            <div class="col-xl-10">
                <div class="row">
                    <div class="col-12">
                        <p>Merchandise Name</p>
                        <h6 class="fw-bold">{{ $merchandise->name }} ({{ $merchandise->type->name }})</h6>
                    </div>
                    <div class="col-12">
                        <a href="{{ route('merchandise.edit', ['merchandise' => $merchandise,]) }}" class="btn btn-success">
                            <i class="icon-note"></i>
                            Edit Merchandise
                        </a>
                        <a href="{{ route('merchandise.delete', ['merchandise' => $merchandise,]) }}" class="btn btn-danger">
                            <i class="icon-trash"></i>
                            Delete Merchandise
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="splitter">
    <div class="card">
        <div class="card-body">
            <a class="btn btn-primary float-end" href="{{ route('merchandise.inventory.create', ['merchandise' => $merchandise,]) }}">
                <i class="icon-plus"></i>
                Create Inventory
            </a>
        </div>
    </div>
    <hr class="splitter">
    <div class="inventory-container">
        @foreach($merchandise->repository->getInventory() as $inventory)
            <div class="card inventory">
                <div class="card-body d-flex">
                    <div class="item">
                        <img src="{{ $inventory->asset }}" class="image thumbnail">
                    </div>
                    <div class="item vertical-divider"></div>
                    <div class="item">
                        <div>
                            <h5 class="fw-bold">{{ $inventory->variant->name }} @isset($inventory->size) @endif</h5>
                        </div>
                        <hr class="splitter">
                        <select onchange="update(this)">
                            <option selected value="{{ $inventory->id }}"
                                    purchase="{{ f_currency($inventory->purchase_price) }}"
                                    sales="{{ f_currency($inventory->sales_price) }}"
                                    stock="{{ $inventory->stock }}"
                                    available="{{ $inventory->available_stock }}"
                                    used="{{ $inventory->used_stock }}"
                                    edit="{{ route('merchandise.inventory.edit', ['merchandise' => $merchandise, 'inventory' => $inventory,]) }}"
                                    delete="{{ route('merchandise.inventory.edit', ['merchandise' => $merchandise, 'inventory' => $inventory,]) }}"
                            >
                                {{ $inventory->size->name }} ({{ f_currency($inventory->sales_price) }})
                            </option>
                            @foreach($inventory->repository->getSizeVariants() as $sizeVariant)
                                <option value="{{ $sizeVariant->id }}"
                                        purchase="{{ f_currency($sizeVariant->purchase_price) }}"
                                        sales="{{ f_currency($sizeVariant->sales_price) }}"
                                        stock="{{ $sizeVariant->stock }}"
                                        available="{{ $sizeVariant->available_stock }}"
                                        used="{{ $sizeVariant->used_stock }}"
                                        edit="{{ route('merchandise.inventory.edit', ['merchandise' => $merchandise, 'inventory' => $sizeVariant,]) }}"
                                        delete="{{ route('merchandise.inventory.edit', ['merchandise' => $merchandise, 'inventory' => $sizeVariant,]) }}"
                                >
                                    {{ $sizeVariant->size->name }} ({{ f_currency($sizeVariant->sales_price) }})
                                </option>
                            @endforeach
                        </select>
                        <hr class="splitter">
                        <div>
                            Purchase: <span class="purchase">{{ f_currency($inventory->purchase_price) }}</span> | Sales: <span class="sales">{{ f_currency($inventory->sales_price) }}</span>
                        </div>
                        <hr class="splitter">
                        <div>
                            Stock: <span class="stock">{{ $inventory->stock }}</span> | Sold: <span class="used">{{ $inventory->used_stock }}</span> | Available: <span class="available">{{ $inventory->available_stock }}</span>
                        </div>
                        @if(!empty($inventory->notes))
                            <hr class="splitter">
                            <div>
                                {{ $inventory->notes }}
                            </div>
                        @endif
                        <hr class="splitter">
                        <div>
                            <a href="{{ route('merchandise.inventory.edit', ['merchandise' => $merchandise, 'inventory' => $inventory,]) }}" class="btn btn-success edit-btn">
                                <i class="icon-note"></i>
                                Edit Inventory
                            </a>
                            <a href="{{ route('merchandise.inventory.edit', ['merchandise' => $merchandise, 'inventory' => $inventory,]) }}" class="btn btn-danger delete-btn">
                                <i class="icon-trash"></i>
                                Delete Inventory
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
