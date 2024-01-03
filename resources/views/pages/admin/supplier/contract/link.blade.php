@php/** @var \App\Models\Supplier\SupplierContract $contract */@endphp

@extends('layout.master')

@section('title', 'View Contract')

@section('content')
    <livewire:admin.supplier.contract.details :contract="$contract" />

    <div class="card">
        <div class="card-body">
            <div class="mb-3 d-flex flex-row content-between justify-between">
                <div class="my-auto">
                    <a href="{{ route('supplier.contract', ['supplier' => $contract->supplier, 'contract' => $contract, ])}}" class="btn btn-primary text-white">
                        {{ Icon::back() }}
                        Back to Contract
                    </a>
                </div>
                <div class="d-flex">
                    <div>
                        <x-livewire.input name="quantity" onchange="Livewire.emit('quantityChanged', this.value)" value="1" label="Quantity" />
                    </div>
                    <div>
                        <x-livewire.input name="cost" onchange="Livewire.emit('costChanged', this.value)" value="1" label="Cost Per Item" />
                    </div>
                </div>
            </div>
            <ul class="nav nav-pills otm-tab flex">
                <li class="nav-item flex-fill">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#accommodation">
                        {{ Icon::accommodation() }} Accommodation
                    </button>
                </li>
                <li class="nav-item flex-fill">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities">
                        {{ Icon::activity() }} Activities
                    </button>
                </li>
                <li class="nav-item flex-fill">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#flights">
                        {{ Icon::flight() }}
                        Flights
                    </button>
                </li>
                <li class="nav-item flex-fill">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transports">
                        {{ Icon::transport() }}
                        Transport
                    </button>
                </li>
                <li class="nav-item flex-fill">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#merchandises">
                        {{ Icon::merchandise() }}
                        Merchandise
                    </button>
                </li>
            </ul>
            {{-- Tables Definition --}}
            <div id="tables" class="tab-content otm-tab-content">
                {{-- Accommodation Table --}}
                <div id="accommodation" role="tabpanel" class="tab-pane fade show active">
                    <livewire:admin.accommodation.inventory.standalone-table :linker="$contract->repository" />
                </div>
                {{-- Activities Table --}}
                <div id="activities" role="tabpanel" class="tab-pane fade">
                    <livewire:admin.activity.inventory.standalone-table :linker="$contract->repository" />
                </div>
                {{-- Flights Table --}}
                <div id="flights" role="tabpanel" class="tab-pane fade">
                    <livewire:admin.flight.inventory.standalone-table :linker="$contract->repository" />
                </div>
                {{-- Transports Table --}}
                <div id="transports" role="tabpanel" class="tab-pane fade">
                    <livewire:admin.transport.inventory.standalone-table :linker="$contract->repository" />
                </div>
                {{-- Merchandises Table --}}
                <div id="merchandises" role="tabpanel" class="tab-pane fade">
                    <livewire:admin.merchandise.inventory.standalone-table :linker="$contract->repository" />
                </div>
            </div>
        </div>
    </div>
@endsection