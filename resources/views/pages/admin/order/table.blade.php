@extends('layout.master')

@section('title', 'All Orders')

@section('content')
    <div class="card">
        <div class="card-body">
            <a class="btn btn-success float-end" href="{{ route('orders.create') }}">
                {{ Icon::create() }}
                <span>Create New</span>
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <livewire:admin.order.table />
        </div>
    </div>
@endsection
