@php
    /**
     * @var \App\Models\Tour\Tour $tour
     * @var \App\Models\Booking\Booking|null $booking
     */
@endphp
@extends('layout.booking.simple', [
    'brand' => $tour->brand,
    'return' => route('booking.simple.index', [
        'token' => $booking->token,
        'tour' => $tour->booking_form_url,
    ])
])

@section('title', "{$tour->name} - {$tour->brand->name}")

@section('check_out_event_name')
<h3 style="display: block!important" class="event-name">{{ $tour->event?->name }}</h3>
<!-- <div class="evnt-name" style="display: block!important">
    <h4 class="head-evnt">{{ $tour->event?->name }}</h4>
</div> -->
@endsection

@section('content')
    <livewire:customer.booking.simple.checkout :tour="$tour" :booking="$booking" />
@endsection
