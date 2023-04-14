@extends('pages.customer.booking.layout')

@php
/**
 * @var \App\Models\Booking\Booking $booking
 */
@endphp

@section('title', 'Confirm Booking')

@section('booking-body')
    <livewire:customer.booking.summary :booking="$booking" key="{{now()}}"/>
@endsection
