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
                <livewire:admin.supplier.associate.table />
            </div>
        </div>
@endsection
