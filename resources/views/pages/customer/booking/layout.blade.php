@extends('layout.customer')

@php
/**
 * @var \App\Models\Tour\Tour $tour
 */
@endphp

@section('content')
    <hr class="splitter">
    <h2 class="col-md-12 mb-0">{{ $tour->name }} - {{ f_currency($tour->base_price_per_person) }} per person</h2>
    <hr class="splitter">
    <script type="text/javascript">
        if ($(window).width() < 960) {
            alert('Please switch to landscape mode for the best experience with this form');
        }
    </script>
    @yield('booking-body')
@endsection
