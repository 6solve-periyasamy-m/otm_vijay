@extends('layout.master')

@section('title', 'Add Components to Quote')

@section('content')
    <x-admin.section.header>
        @include('partials.admin.quote.details', ['quote' => $quote])
    </x-admin.section.header>
    
    <hr class="splitter"/>
    {{-- Tabs Definition --}}
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('quotes.view', ['quote' => $quote, ])}}" class="btn btn-primary text-white">
                    {{ Icon::back() }}
                    Back to Quote
                </a>
            </div>
            <ul class="nav nav-pills otm-tab">
                <li class="nav-item col-6 col-md-3">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#accommodation">
                        {{ Icon::accommodation() }} Accommodation
                    </button>
                </li>
                <li class="nav-item col-6 col-md-3">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities">
                        {{ Icon::activity() }} Activities
                    </button>
                </li>
                <li class="nav-item col-6 col-md-3">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#flights">
                        {{ Icon::flight() }}
                        Flights
                    </button>
                </li>
                <li class="nav-item col-6 col-md-3">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transports">
                        {{ Icon::transport() }}
                        Transport
                    </button>
                </li>
            </ul>
            {{-- Tables Definition --}}
            <div id="tables" class="tab-content otm-tab-content">
                {{-- Accommodation Table --}}
                <div id="accommodation" role="tabpanel" class="tab-pane fade show active">
                    @include('partials.components.accommodation.quote.table')
                </div>
                {{-- Activities Table --}}
                <div id="activities" role="tabpanel" class="tab-pane fade">
                    @include('partials.components.activity.quote.table')
                </div>
                {{-- Flights Table --}}
                <div id="flights" role="tabpanel" class="tab-pane fade">
                    @include('partials.components.flights.quote.table')
                </div>
                {{-- Transports Table --}}
                <div id="transports" role="tabpanel" class="tab-pane fade">
                    @include('partials.components.transport.quote.table')
                </div>
            </div>
        </div>
    </div>
@endsection
