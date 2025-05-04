@php
/** @var \App\Models\Booking\Booking $booking */
/** @var \App\Models\Tour\Tour|null $tour */
$tour = $booking->tour;
@endphp
@extends('layout.master')

@section('title', 'View Booking')

@section('content')
    <div class="otm-callout">
        <div class="row">
            <x-admin.section.otm-text width="4">
                <x-slot:header>Tour</x-slot:header>
                {{ $tour?->name ?? 'Tour Not Found' }}
            </x-admin.section.otm-text>
            <x-admin.section.otm-text width="4">
                <x-slot:header>Event</x-slot:header>
                {{ $tour?->event?->name ?? 'Event Not Found' }}
            </x-admin.section.otm-text>
            <x-admin.section.otm-text width="4">
                <x-slot:header>Booking Link</x-slot:header>
                @if($tour !== null)
                    <a href="{{ $tour->getBookingFormUrl($booking) }}">Click Here</a>
                @else
                    Tour Not Found
                @endif
            </x-admin.section.otm-text>
            <x-admin.section.otm-text width="4">
                <x-slot:header>Travellers</x-slot:header>
                {{ $booking->travellers->count() }}
            </x-admin.section.otm-text>
            <x-admin.section.otm-text width="8">
                <x-slot:header>Due Today</x-slot:header>
                {{ f_currency($booking->due_today) }}
            </x-admin.section.otm-text>
            <x-admin.section.otm-text width="4">
                <x-slot:header>Total Cost</x-slot:header>
                {{ f_currency($booking->total_cost) }}
            </x-admin.section.otm-text>
            <x-admin.section.otm-text width="4">
                <x-slot:header>Surcharge</x-slot:header>
                {{ f_currency($booking->repository->getSingleOccupancyAmount()) }}
            </x-admin.section.otm-text>
            <x-admin.section.otm-text width="4">
                <x-slot:header>Taxes</x-slot:header>
                {{ f_currency($booking->repository->getTaxes()) }}
            </x-admin.section.otm-text>
            <div class="col-12">
                <button class="btn btn-primary" onclick="$('.convert-form').submit()">
                    Convert to Order
                </button>
                <form class="convert-form" style="display: none;" action="{{ route('admin.booking.convert', ['booking' => $booking]) }}" method="post">@csrf</form>
            </div>
        </div>
    </div>
    <hr class="splitter" />
    <x-admin.section.card>
        <x-slot:title>Travellers</x-slot:title>
        <table class="table datatable table-striped">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Lead Booker</th>
                    <th scope="col">Role</th>
                    <th scope="col">Cost</th>
                    <th scope="col">Single Occupancy</th>
                </tr>
            </thead>
            <tbody>
                @foreach($booking->travellers as $traveller)
                    <tr>
                        <th scope="row">{{ $traveller->full_name }}</th>
                        <td>{{ f_bool($traveller->id === $booking->lead_traveller_id) }}</td>
                        <td>{{ $traveller->role->name }}</td>
                        <td>{{ $traveller->repository->getTotalCost() }}</td>
                        <td>{{ $traveller->repository->getSingleOccupancy() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-admin.section.card>
    <x-admin.section.card>
        <x-slot:title>Groups</x-slot:title>
        @foreach($booking->groups as $group)
            <hr class="splitter" />
            <div class="row">
                <div class="col-3">
                    <h4 class="fw-bold">Travellers</h4>
                    @foreach($group->travellers as $traveller)
                        {{ $traveller->full_name }}<br />
                    @endforeach
                </div>
                <div class="col-9">
                    <h4 class="fw-bold">Rooms</h4>
                    @foreach($group->accommodation as $room)
                        {{ $room->tourComponent }}<br />
                    @endforeach
                </div>
            </div>
        @endforeach
    </x-admin.section.card>
    <div class="row">
        @foreach($booking->travellers as $traveller)
            <div class="col-xl-6">
                <x-admin.section.card>
                    <x-slot:title>{{ $traveller->full_name }}</x-slot:title>
                    @foreach($traveller->repository->getComponents(false) as $component)
                        {{ $component }}
                        <br />
                    @endforeach
                </x-admin.section.card>
            </div>
        @endforeach
    </div>
@endsection
