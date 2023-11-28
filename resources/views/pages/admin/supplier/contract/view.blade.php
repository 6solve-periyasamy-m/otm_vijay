@php/** @var \App\Models\Supplier\SupplierContract $contract */@endphp

@extends('layout.master')

@section('title', 'View Contract')

@section('content')
    <livewire:admin.supplier.contract.details :contract="$contract" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="fw-bold">
                            Contracted Components
                        </h4>
                    </div>
                    <ul class="nav nav-pills otm-tab">
                        <li class="nav-item col-6 col-md-3">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#accommodation">
                                {{ Icon::accommodation() }} Accommodation
                            </button>
                        </li>
                        <li class="nav-item col-6 col-md-3">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities">
                                {{ Icon::activity() }} Activities
                            </button>
                        </li>
                        <li class="nav-item col-6 col-md-3">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#flights">
                                {{ Icon::flight() }}
                                Flights
                            </button>
                        </li>
                        <li class="nav-item col-6 col-md-3">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transports">
                                {{ Icon::transport() }}
                                Transport
                            </button>
                        </li>
                    </ul>
                    {{-- Tables Definition --}}
                    <div id="tables" class="tab-content otm-tab-content">
                        {{-- Accommodation Table --}}
                        <div id="accommodation" role="tabpanel" class="tab-pane fade show active">
                            <livewire:admin.supplier.contract.component.accommodation-table :contract="$contract" />
                        </div>
                        {{-- Activities Table --}}
                        <div id="activities" role="tabpanel" class="tab-pane fade">
                            <livewire:admin.supplier.contract.component.activity-table :contract="$contract" />
                        </div>
                        {{-- Flights Table --}}
                        <div id="flights" role="tabpanel" class="tab-pane fade">
                            <livewire:admin.supplier.contract.component.flight-table :contract="$contract" />
                        </div>
                        {{-- Transports Table --}}
                        <div id="transports" role="tabpanel" class="tab-pane fade">
                            <livewire:admin.supplier.contract.component.transport-table :contract="$contract" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="fw-bold">
                            Supplier Payments
                        </h4>
                        <div class="float-end">
                            <a class="btn btn-success" onclick="openModal('admin.supplier.contract.payment.form', {'contract': {{$contract->id}},})">
                                {{ Icon::create() }} {{ __('supplier.contract.payment.create') }}
                            </a>
                        </div>
                    </div>
                    <livewire:admin.supplier.contract.payment.table :contract="$contract" />
                </div>
            </div>
        </div>
    </div>


@endsection