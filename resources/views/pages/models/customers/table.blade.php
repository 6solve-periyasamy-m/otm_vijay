@extends('layout.master')

@section('title', 'All Customers')

@section('content')
    <x-admin.section.card>
        <div class="text-end">
            <a class="btn btn-primary text-white" href="{{ route('customers.create') }}">
                {{ Icon::create() }}
                Create New
            </a>
            &nbsp;&nbsp;
            <a href="{{ route('customers.export') }}" class="btn btn-success">
                {!! Icon::excel() !!}
                <span>Export Customers</span>
            </a>
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <livewire:admin.customer.table />
    </x-admin.section.card>
@endsection
