@extends('layout.master')

@section('title', 'Add Components to Tour')

@section('content')
    <div class="otm-callout">
        <div class="row">
            <div class="col-12">
                <h4 class="fw-bold">{{ $tour->name }}</h4>
            </div>
            <div class="col-12">
                <p>Event</p>
                <h6 class="fw-bold">{{ isset($tour->event) ? $tour->event->name : "No Event" }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Price per Person</p>
                <h6 class="fw-bold">{{ $tour->base_price_per_person }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Single Occupancy Surcharge</p>
                <h6 class="fw-bold">{{ $tour->single_occupancy_surcharge }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>From</p>
                <h6 class="fw-bold">{{ f_date($tour->date_from) }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>To</p>
                <h6 class="fw-bold">{{ f_date($tour->date_to) }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Margin</p>
                <h6 class="fw-bold">{{ $tour->margin }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Is Active</p>
                <h6 class="fw-bold">{{ $tour->is_active ? "Yes" : "No" }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Internal Notes</p>
                <h6 class="fw-bold">{{ $tour->notes }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>External Notes</p>
                <h6 class="fw-bold">{{ $tour->notes }}</h6>
            </div>                
            <div class="col-12 col-xl-6">
                <p>Description</p>
                <h6 class="fw-bold">{{ $tour->description }}</h6>
            </div>        
        </div>
    </div>
    <hr class="splitter"/>
    {{-- Tabs Definition --}}
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('tours.view', ['tour' => $tour, ])}}" class="btn btn-primary text-white">
                    <i class="icon-arrow-left"></i>
                    Back to Tour
                </a>
            </div>
            <ul class="nav nav-pills otm-tab">
                <li class="nav-item col-6 col-md-2">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#accommodation">
                        <i class="icon-home"></i> Accommodation
                    </button>
                </li>
                <li class="nav-item col-6 col-md-2">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities">
                        <i class="icon-game-controller"></i> Activities
                    </button>
                </li>
                <li class="nav-item col-6 col-md-2">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#flights">
                        <i class="icon-plane"></i>
                        Flights
                    </button>
                </li>
                <li class="nav-item col-6 col-md-2">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transports">
                        <i class="icon-directions"></i>
                        Transport
                    </button>
                </li>
                <li class="nav-item col-6 col-md-2">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#merchandise">
                        <i class="icon-mustache"></i>
                        Merchandise
                    </button>
                </li>
            </ul>
            {{-- Tables Definition --}}
            <div id="tables" class="tab-content otm-tab-content">
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
                {{-- Merchandise Table --}}
                <div id="merchandise" role="tabpanel" class="tab-pane fade">
                    @include('partials.components.merchandise.tour.table')
                </div>
            </div>
        </div>
    </div>
@endsection
