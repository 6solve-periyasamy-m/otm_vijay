@php
    /**
     * @param \App\Models\Supplier\Supplier $supplier;
     */
@endphp

@extends('layout.master')

@section('title', 'View Supplier')

@section('content')
    <livewire:admin.supplier.details :supplier="$supplier" />

    <div class="card">
        <div class="card-body">
            <div class="card-title flex justify-content-between">
                <h4 class="fw-bold">{{ __('supplier.associate.title') }}</h4>
                <div class="float-end">
                    <a class="btn btn-success" onclick="openModal('admin.supplier.associate.form', {'supplier': {{$supplier->id}},})">
                        {{ Icon::create() }} {{ __('supplier.associate.buttons.create') }}
                    </a>
                </div>
            </div>
            <livewire:admin.supplier.associate.table :supplier="$supplier->id" />
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="card-title flex justify-content-between">
                <h4 class="fw-bold">{{ __('supplier.contract.title') }}</h4>
                <div class="float-end">
                    <a class="btn btn-success" onclick="openModal('admin.supplier.contract.form', {'supplier': {{$supplier->id}},})">
                        {{ Icon::create() }} {{ __('supplier.contract.create') }}
                    </a>
                </div>
            </div>
            <livewire:admin.supplier.contract.table :supplier="$supplier" />
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="card-title flex justify-content-between">
                <h4 class="fw-bold">{{ __('supplier.payment.title') }}</h4>
                <div class="float-end">
                    <a class="btn btn-success" onclick="openModal('admin.supplier.payment.form', {'supplier': {{$supplier->id}},})">
                        {{ Icon::create() }} {{ __('supplier.payment.create') }}
                    </a>
                </div>
            </div>
            <livewire:admin.supplier.payment.table :supplier="$supplier" />
        </div>
    </div>
@endsection
