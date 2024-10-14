@php
/**
 * @var \App\Models\Tour\Tour $tour
 * @var \App\Models\Booking\Booking|null $booking
 */
@endphp
@extends('layout.booking.simple', ['brand' => $tour->brand,])

@section('title', "{$tour->name} - {$tour->brand->name}")

@section('content')
    <livewire:customer.booking.simple.rooming :tour="$tour" :booking="$booking" />
@endsection
