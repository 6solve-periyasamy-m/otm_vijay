@extends('layout.main')

@section('title', 'Add Components to Tour')

@section('content')
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
