@extends('layout.master')

@section('title', 'Order Report')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <h4 class="fw-bold">Orders</h4>
            </div>
            <livewire:admin.report.order-report />
        </div>
    </div>
@endsection
