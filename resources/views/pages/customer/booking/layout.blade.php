@php
/**
 * @var \App\Models\Tour\Tour $tour
 */
@endphp

@extends('layout.customer', ['branding' => $tour->brand])

@section('content')
    <hr class="splitter">
    <h2 class="col-md-12 mb-0">{{ $tour->name }} - {{ f_currency($tour->base_price_per_person) }} per person</h2>
    <hr class="splitter">
    @yield('booking-body')
@endsection
