@extends('layout.customer')

@php
/**
 * @var \App\Models\Tour $tour
 */
@endphp

@section('content')
    <hr class="splitter">
    <h2 class="col-md-12 mb-0">{{ $tour->name }} - {{ StringFormatter::formatCurrency($tour->base_price_per_person) }} per person</h2>
    <hr class="splitter">
    <div class="card">
        <div class="card-body">
            @yield('booking-body')
        </div>
    </div>
@endsection
