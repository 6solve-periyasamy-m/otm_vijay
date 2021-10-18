@extends('layout.main')

@section('title', 'Add Components to Tour')

@section('content')
    <table style="width: 100%">
        <tr>
            <td style="border: 1px solid black">{{ $tour->title }}</td>
            <td style="border: 1px solid black">Price per Person: {{ $tour->base_price_per_person }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black">Event: {{ $tour->event->event_title }}</td>
            <td style="border: 1px solid black">Single Occupancy Surcharge: {{ $tour->single_occupancy_surcharge }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black">From: {{ $tour->date_from }}</td>
            <td style="border: 1px solid black">To: {{ $tour->date_to }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black">Margin: {{ $tour->margin }}</td>
            <td style="border: 1px solid black">Is Active: {{ $tour->is_active ? "Yes" : "No" }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black">Internal Notes: {{ $tour->notes }}</td>
            <td style="border: 1px solid black">External Notes: {{ $tour->notes }}</td>
        </tr>
    </table>
    {{ $tour->description }}
    <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
    {{-- Tabs Definition --}}
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#accommodation">Accommodation</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities">Activities</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#flights">Flights</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transports">Transports</button>
        </li>
    </ul>
    {{-- Tables Definition --}}
    <div id="tables" class="tab-content" style="padding: 5px">
        {{-- Accommodation Table --}}
        <div id="accommodation" role="tabpanel" class="tab-pane fade show active">
            @include('partials.components.accommodation.tour.table')
        </div>
        {{-- Activities Table --}}
        <div id="activities" role="tabpanel" class="tab-pane fade">
            @include('partials.components.activity.tour.table')
        </div>
        {{-- Flights Table --}}
        <div id="flights" role="tabpanel" class="tab-pane fade">
            @include('partials.components.flights.tour.table')
        </div>
        {{-- Transports Table --}}
        <div id="transports" role="tabpanel" class="tab-pane fade">
            @include('partials.components.transport.tour.table')
        </div>
    </div>
@endsection
