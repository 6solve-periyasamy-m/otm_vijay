@extends('layout.master')

@section('title', 'Order Report')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <h4 class="fw-bold">Quote Finances</h4>
            </div>
            <livewire:admin.report.quote.finances />
        </div>
    </div>
@endsection
