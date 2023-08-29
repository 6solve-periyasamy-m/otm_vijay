@php
    /**
     * @param \App\Models\Supplier\Supplier $supplier;
     */
@endphp

@extends('layout.master')

@section('title', 'View Supplier')

@section('content')
    <livewire:admin.supplier.details :supplier="$supplier" />
    <div class="row">

    </div>
@endsection
