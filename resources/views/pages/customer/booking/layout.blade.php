@php
/**
 * @var \App\Models\Tour\Tour $tour
 */
@endphp

@extends('layout.customer', ['branding' => $tour->brand])

@section('content')
    <div class="card">
        <div class="card-body booking-title">
            {{ $tour->name }} - {{ f_currency($tour->base_price_per_person) }} per person
        </div>
    </div>
    @yield('booking-body')
@endsection
