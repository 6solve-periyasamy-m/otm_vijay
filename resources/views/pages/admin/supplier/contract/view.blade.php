@php/** @var \App\Models\Supplier\SupplierContract $contract */@endphp

@extends('layout.master')

@section('title', 'View Contract')

@section('content')
    <livewire:admin.supplier.contract.details :contract="$contract" />
@endsection