@php
/**
 * @var \App\Models\Tour\Tour $tour
 * @var \App\Models\Booking\Booking $booking
 */
use App\Models\Helper\Enum\ActivityCategory;
@endphp

@extends('layout.booking.v3')

@section('content')
    <livewire:customer.booking.v3.ticket :tour="$tour" :booking="$booking" />
@endsection