@extends('layout.master')

@section('title', 'All Orders')

@section('content')
    <x-admin.section.card>
        <a class="btn btn-success float-end" href="{{ route('orders.create') }}">
                {{ Icon::create() }}
                <span>Create New</span>
            </a>
        </x-admin.section.card>
    <x-admin.section.card>
        <livewire:admin.order.table />
    </x-admin.section.card>
@endsection
