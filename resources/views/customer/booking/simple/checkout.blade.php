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

@section('content')
    <livewire:customer.booking.simple.checkout :tour="$tour" :booking="$booking" />
@endsection
