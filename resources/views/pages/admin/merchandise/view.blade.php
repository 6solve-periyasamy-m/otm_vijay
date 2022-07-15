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
            max-width: 100px;
            max-height: 100px;
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
        .alternator {
            display: flex !important;
        }
        .alternator:nth-child(evn) {
            flex-direction: row-reverse;
        }
        .vertical-divider {
            width: 1px;
            border-right: 5px solid #cccccc;
            border-radius: 2px;
        }
    </style>
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
                            <h5 class="fw-bold">{{ $inventory->variant->name }}</h5>
                        </div>
                        <hr class="splitter">
                        <div>
                            Stock: {{ $inventory->stock }} | Sold: {{ $inventory->used_stock }} | Available: {{ $inventory->available_stock }}
                        </div>
                        <hr class="splitter">
                        <div>
                            <a href="{{ route('merchandise.inventory.edit', ['merchandise' => $merchandise, 'inventory' => $inventory,]) }}" class="btn btn-success">
                                <i class="icon-note"></i>
                                Edit Inventory
                            </a>
                            <a href="{{ route('merchandise.inventory.edit', ['merchandise' => $merchandise, 'inventory' => $inventory,]) }}" class="btn btn-danger">
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
