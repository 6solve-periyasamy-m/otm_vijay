@extends('layout.master')

@section('title', 'All Customers')

@section('content')
    <x-admin.section.card>
        <div class="text-end">
            <a class="btn btn-primary text-white" href="{{ route('customers.create') }}">
                {{ Icon::create() }}
                Create New
            </a>
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <livewire:admin.customer.table />
    </x-admin.section.card>
@endsection
