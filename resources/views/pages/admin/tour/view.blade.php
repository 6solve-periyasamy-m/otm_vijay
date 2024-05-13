@php use App\Models\Quote\Quote; @endphp
@php use App\Models\Tour\Tour; @endphp
@php use App\Models\Accommodation\AccommodationInventoryTour; @endphp
@php use App\Models\Activity\ActivityInventoryTour; @endphp
@php use App\Models\Flight\FlightInventoryTour; @endphp
@php use App\Models\Transport\TransportInventoryTour; @endphp
@php use App\Models\Merchandise\Merchandise; @endphp
@extends('layout.master')

@php
    /**
     * @var Tour $tour
     */
@endphp

@section('title', 'View Tour')

@section('content')
    @include('partials.admin.tour.popup')   
    <div class="otm-callout">
        <div class="row">
            <div class="col-12">
                <h4 class="fw-bold">{{ $tour->name }}</h4>
            </div>
            <div class="col-12 col-xl-6">
                <p>Event</p>
                <h6 class="fw-bold">{{ isset($tour->event) ? $tour->event->name : "None" }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Booking URL</p>
                <h6 class="fw-bold">
                    @if(isset($tour->booking_form_url))
                        <a target="_blank"
                           href="{{ route('customer-booking.index', ['bookingUrl' => $tour->booking_form_url,]) }}">{{ route('customer-booking.index', ['bookingUrl' => $tour->booking_form_url,]) }}</a>
                    @else
                        No Booking URL set
                    @endif
                </h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Price per Person</p>
                <h6 class="fw-bold">{{ f_currency($tour->base_price_per_person) }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Single Occupancy Surcharge</p>
                <h6 class="fw-bold">{{ f_currency($tour->single_occupancy_surcharge) }}</h6>
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
                <h6 class="fw-bold">{{ f_currency($tour->margin) }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Is Active</p>
                <h6 class="fw-bold">{{ $tour->is_active ? "Yes" : "No" }}</h6>
            </div>
            <div class="col-12 col-xl-12">
                <p>Notes</p>
                <h6 class="fw-bold">{{ $tour->notes }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Description</p>
                <h6 class="fw-bold">{{ $tour->description }}</h6>
            </div>
            <div class="col-12">
                @can('update', \App\Models\Tour\Tour::class)
                    <a class="btn btn-warning" href="{{route('tours.edit', ['tour' => $tour,])}}">
                        {{ Icon::edit() }}
                        <span>Edit Tour</span>
                    </a>
                @endcan
                @can('create', \App\Models\Quote\Quote::class)
                    <a class="btn btn-primary" href="{{route('quotes.create', ['tour' => $tour,])}}">
                        {{ Icon::quote() }}
                        <span>Create Quote</span>
                    </a>
                @endcan
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#optionTour">
                    {{ Icon::options() }}
                    <span>Options</span>
                </button>
            </div>
        </div>
    </div>    
    <hr class="splitter"/>
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Components</h2>
    </div>
    <x-admin.section.card>
        <div class="py-2 mb-3 text-end">
            <a href="{{ route('tours.add', ['tour' => $tour, ]) }}" class="btn btn-primary text-white">
                {{ Icon::create() }}
                <span>Add Components</span>
            </a>
        </div>
        {{-- Tabs Definition --}}
        <ul class="nav nav-pills otm-tab">
            <li class="nav-item col-6 col-md-3">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#accommodation">
                    {{ Icon::accommodation() }}
                    Accommodation
                </button>
            </li>
            <li class="nav-item col-6 col-md-3">    
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities">
                    {{ Icon::activity() }}
                    Activities
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
                <div id="accommodation-details">
                    <table id="accommodation-table" class="datatable table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">Room Type</th>
                            <th scope="col">Board Type</th>
                            <th scope="col">Template?</th>
                            <th scope="col">Component Type</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Bookable?</th>
                            <th scope="col">Stock Controlled?</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        @foreach($tour->accommodationInventoryTours as $tourComponent)
                            <tr>
                                <td style="min-width: 200px">
                                    {{ f_datetime($tourComponent->inventory->check_in) }}
                                    <input type="checkbox" disabled
                                           @if($tourComponent->inventory->check_in_time_confirmed == 1) checked @endif>
                                    &nbsp;to&nbsp;
                                    {{ f_datetime($tourComponent->inventory->check_out) }}
                                    <input type="checkbox" disabled
                                           @if($tourComponent->inventory->check_out_time_confirmed == 1) checked @endif>
                                </td>
                                <td>{{ $tourComponent->inventory->component->name }}</td>
                                <td>{{ $tourComponent->inventory->roomType->name }}</td>
                                <td>{{ $tourComponent->inventory->boardType->name }}</td>
                                <td>{{ f_bool($tourComponent->is_template) }}</td>
                                <td>
                                    @if($tourComponent->tour_component_type == 'Upgrade')
                                        <abbr title="{{ $tourComponent->parent() }}">
                                            @endif
                                            {{ $tourComponent->tour_component_type }}
                                            @if($tourComponent->tour_component_type == 'Upgrade')
                                        </abbr>
                                    @endif
                                </td>
                                <td>
                                    {{ $tourComponent->repository->getUsedStock() }}
                                    /{{ $tourComponent->repository->getTotalStock() }}<br/>
                                    ({{$tourComponent->repository->getAvailableStock()}} Available)
                                </td>
                                <td>{{ f_bool($tourComponent->is_bookable) }}</td>
                                <td>{{ f_bool($tourComponent->stock_control_active) }}</td>
                                <td class="actions-3">
                                    @can('update', AccommodationInventoryTour::class)
                                        @if($tourComponent->tour_component_type !== 'Add-on')
                                            <a href="{{ route('accommodation-upgrade.view', ['tour' => $tour, 'inventoryTour' => $tourComponent->tour_component_type == 'Upgrade' ? $tourComponent->parent() : $tourComponent,]) }}"
                                               class="btn btn-outline-success btn-sm mb-1" title="Upgrade">{{ Icon::upgrade() }}</a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                    {{ Icon::upgrade() }}
                                                </span>
                                        @endif
                                        <a href="{{ route('accommodation-inventory-tours.edit', ['tour' => $tour, 'accommodationInventoryTour' => $tourComponent,]) }}"
                                           class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                                    @else
                                        <span class="btn btn-outline-dark btn-sm mb-1">
                                                {{ Icon::upgrade() }}
                                            </span>
                                        <span class="btn btn-outline-dark btn-sm mb-1">
                                                {{ Icon::edit() }}
                                            </span>
                                    @endcan
                                    @can('delete', AccommodationInventoryTour::class)
                                        @if($tourComponent->is_bookable)
                                            <a href="#"
                                               onclick="$('#accommodation-{{$tourComponent->id}}-delete').submit()"
                                               class="btn btn-outline-danger btn-sm mb-1" title="Delete">{{ Icon::delete() }}</a>
                                            <form action="{{ route('accommodation-inventory-tours.delete', ['tour' => $tour, 'accommodationInventoryTour' => $tourComponent,]) }}"
                                                  method="post"
                                                  id="accommodation-{{$tourComponent->id}}-delete">
                                                @csrf
                                            </form>
                                        @else
                                            <a href="#"
                                               onclick="$('#accommodation-{{$tourComponent->id}}-restore').submit()"
                                               class="btn btn-outline-warning btn-sm mb-1">
                                                {{ Icon::bookable() }}</a>
                                            <form action="{{ route('accommodation-inventory-tours.restore', ['tour' => $tour, 'accommodationInventoryTour' => $tourComponent,]) }}"
                                                  method="post"
                                                  id="accommodation-{{$tourComponent->id}}-restore">
                                                @csrf
                                            </form>
                                        @endif
                                    @else
                                        <span class="btn btn-outline-dark btn-sm mb-1">
                                                {{ Icon::delete() }}
                                            </span>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            {{-- Activities Table --}}
            <div id="activities" role="tabpanel" class="tab-pane fade">
                <div id="activities-details">
                    <table id="activities-table" class="datatable table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">Activity Type</th>
                            <th scope="col">Ticket Type</th>
                            <th scope="col">Component Type</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Bookable?</th>
                            <th scope="col">Stock Controlled?</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        @foreach($tour->activityInventoryTours as $tourComponent)
                            <tr>
                                <td style="min-width: 200px">{{ f_datetime($tourComponent->inventory->starts_at) }}
                                    to {{ f_datetime($tourComponent->inventory->ends_at) }}</td>
                                <td>{{ $tourComponent->inventory->component->name }}</td>
                                <td>{{ $tourComponent->inventory->component->activityType->name }}</td>
                                <td>{{ $tourComponent->inventory->ticketType->name }}</td>
                                <td>
                                    @if($tourComponent->tour_component_type == 'Upgrade')
                                        <abbr title="{{ $tourComponent->parent() }}">
                                            @endif
                                            {{ $tourComponent->tour_component_type }}
                                            @if($tourComponent->tour_component_type == 'Upgrade')
                                        </abbr>
                                    @endif
                                </td>
                                <td>
                                    {{ $tourComponent->repository->getUsedStock() }}
                                    /{{ $tourComponent->repository->getTotalStock() }}<br/>
                                    ({{$tourComponent->repository->getAvailableStock()}} Available)
                                </td>
                                <td>{{ f_bool($tourComponent->is_bookable) }}</td>
                                <td>{{ f_bool($tourComponent->stock_control_active) }}</td>
                                <td class="actions-3">
                                    @can('update', ActivityInventoryTour::class)
                                        @if($tourComponent->tour_component_type !== 'Add-on')
                                            <a href="{{ route('activity-upgrade.view', ['tour' => $tour, 'inventoryTour' => $tourComponent->tour_component_type == 'Upgrade' ? $tourComponent->parent() : $tourComponent,]) }}"
                                               class="btn btn-outline-success btn-sm mb-1" title="Upgrade">{{ Icon::upgrade() }}</a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1" title="Upgrade">
                                                    {{ Icon::upgrade() }}
                                                </span>
                                        @endif
                                        <a href="{{ route('activity-inventory-tours.edit', ['tour' => $tour, 'activityInventoryTour' => $tourComponent,]) }}"
                                           class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                                    @else
                                        <span class="btn btn-outline-dark btn-sm mb-1" title="Upgrade">
                                                    {{ Icon::upgrade() }}
                                                </span>
                                        <span class="btn btn-outline-dark btn-sm mb-1" title="Edit">
                                                {{ Icon::edit() }}
                                            </span>
                                    @endcan
                                    @can('delete', ActivityInventoryTour::class)
                                        @if($tourComponent->is_bookable)
                                            <a href="#"
                                               onclick="$('#activity-{{$tourComponent->id}}-delete').submit()"
                                               class="btn btn-outline-danger btn-sm mb-1" title="Delete">{{ Icon::delete() }}</a>
                                            <form action="{{ route('activity-inventory-tours.delete', ['tour' => $tour, 'activityInventoryTour' => $tourComponent,]) }}"
                                                  method="post" id="activity-{{$tourComponent->id}}-delete">
                                                @csrf
                                            </form>
                                        @else
                                            <a href="#"
                                               onclick="$('#activity-{{$tourComponent->id}}-restore').submit()"
                                               class="btn btn-outline-warning btn-sm mb-1">{{ Icon::bookable() }}</a>
                                            <form action="{{ route('activity-inventory-tours.restore', ['tour' => $tour, 'activityInventoryTour' => $tourComponent,]) }}"
                                                  method="post" id="activity-{{$tourComponent->id}}-restore">
                                                @csrf
                                            </form>
                                        @endif
                                    @else
                                        <span class="btn btn-outline-dark btn-sm mb-1" title="Delete">
                                                {{ Icon::delete() }}
                                            </span>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            {{-- Flights Table --}}
            <div id="flights" role="tabpanel" class="tab-pane fade">
                <div id="flights-details">
                    <table id="flights-table" class="datatable table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Details</th>
                            <th scope="col">Airline</th>
                            <th scope="col">Travel Class</th>
                            <th scope="col">Flight Type</th>
                            <th scope="col">Component Type</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Bookable?</th>
                            <th scope="col">Stock Controlled?</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        @foreach($tour->flightInventoryTours as $tourComponent)
                            <tr>
                                <td style="min-width: 200px">{{ f_datetime($tourComponent->inventory->departs_at) }}
                                    to {{ f_datetime($tourComponent->inventory->arrives_at) }}</td>
                                <td>{{ $tourComponent->inventory->flight->departureAirport->name }} to {{ $tourComponent->inventory->flight->arrivalAirport->name }} ({{ $tourComponent->inventory->flight_number }})</td>
                                <td>{{ $tourComponent->inventory->flight->airline->name }}</td>
                                <td>{{ $tourComponent->inventory->travelClass->name }}</td>
                                <td>{{ $tourComponent->flight_type }}</td>
                                <td>
                                    @if($tourComponent->tour_component_type == 'Upgrade')
                                        <abbr title="{{ $tourComponent->parent() }}">
                                            @endif
                                            {{ $tourComponent->tour_component_type }}
                                            @if($tourComponent->tour_component_type == 'Upgrade')
                                        </abbr>
                                    @endif
                                </td>
                                <td>
                                    {{ $tourComponent->repository->getUsedStock() }}
                                    /{{ $tourComponent->repository->getTotalStock() }}<br/>
                                    ({{$tourComponent->repository->getAvailableStock()}} Available)
                                </td>
                                <td>{{ f_bool($tourComponent->is_bookable) }}</td>
                                <td>{{ f_bool($tourComponent->stock_control_active) }}</td>
                                <td class="actions-3">
                                    @can('update', FlightInventoryTour::class)
                                        @if($tourComponent->tour_component_type !== 'Add-on')
                                            <a href="{{ route('flight-upgrade.view', ['tour' => $tour, 'inventoryTour' => $tourComponent->tour_component_type == 'Upgrade' ? $tourComponent->parent() : $tourComponent,]) }}"
                                               class="btn btn-outline-success btn-sm mb-1" title="Upgrade">{{ Icon::upgrade() }}</a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1" title="Upgrade">
                                                    {{ Icon::upgrade() }}
                                                </span>
                                        @endif
                                        <a href="{{ route('flight-inventory-tours.edit', ['tour' => $tour, 'flightInventoryTour' => $tourComponent,]) }}"
                                           class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                                    @else
                                        <span class="btn btn-outline-dark btn-sm mb-1">
                                                    {{ Icon::upgrade() }}
                                                </span>
                                        <span class="btn btn-outline-dark btn-sm mb-1">
                                                {{ Icon::edit() }}
                                            </span>
                                    @endcan
                                    @can('delete', FlightInventoryTour::class)
                                        @if($tourComponent->is_bookable)
                                            <a href="#"
                                               onclick="$('#flight-{{$tourComponent->id}}-delete').submit()"
                                               class="btn btn-outline-danger btn-sm mb-1" title="Delete">{{ Icon::delete() }}</a>
                                            <form action="{{ route('flight-inventory-tours.delete', ['tour' => $tour, 'flightInventoryTour' => $tourComponent,]) }}"
                                                  method="post" id="flight-{{$tourComponent->id}}-delete">
                                                @csrf
                                            </form>
                                        @else
                                            <a href="#"
                                               onclick="$('#flight-{{$tourComponent->id}}-restore').submit()"
                                               class="btn btn-outline-warning btn-sm mb-1">{{ Icon::bookable() }}</a>
                                            <form action="{{ route('flight-inventory-tours.restore', ['tour' => $tour, 'flightInventoryTour' => $tourComponent,]) }}"
                                                  method="post" id="flight-{{$tourComponent->id}}-restore">
                                                @csrf
                                            </form>
                                        @endif
                                    @else
                                        <span class="btn btn-outline-dark btn-sm mb-1">
                                                {{ Icon::delete() }}
                                            </span>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            {{-- Transports Table --}}
            <div id="transports" role="tabpanel" class="tab-pane fade">
                <div id="transports-details">
                    <table id="transports-table" class="datatable table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">Transport Number</th>
                            <th scope="col">Travel Class</th>
                            <th scope="col">Component Type</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Bookable?</th>
                            <th scope="col">Stock Controlled?</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        @foreach($tour->transportInventoryTours as $tourComponent)
                            <tr>
                                <td style="min-width: 200px">{{ f_datetime($tourComponent->inventory->departs_at) }}
                                    to {{ f_datetime($tourComponent->inventory->arrives_at) }}</td>
                                <td>{{ $tourComponent->inventory->component->name }}</td>
                                <td>{{ $tourComponent->inventory->transport_number }}</td>
                                <td>{{ $tourComponent->inventory->travelClass->name }}</td>
                                <td>
                                    @if($tourComponent->tour_component_type == 'Upgrade')
                                        <abbr title="{{ $tourComponent->parent() }}">
                                            @endif
                                            {{ $tourComponent->tour_component_type }}
                                            @if($tourComponent->tour_component_type == 'Upgrade')
                                        </abbr>
                                    @endif
                                </td>
                                <td>
                                    {{ $tourComponent->repository->getUsedStock() }}
                                    /{{ $tourComponent->repository->getTotalStock() }}<br/>
                                    ({{$tourComponent->repository->getAvailableStock()}} Available)
                                </td>
                                <td>{{ f_bool($tourComponent->is_bookable) }}</td>
                                <td>{{ f_bool($tourComponent->stock_control_active) }}</td>
                                <td class="actions-3">
                                    @can('update', TransportInventoryTour::class)
                                        @if($tourComponent->tour_component_type !== 'Add-on')
                                            <a href="{{ route('transport-upgrade.view', ['tour' => $tour, 'inventoryTour' => $tourComponent->tour_component_type == 'Upgrade' ? $tourComponent->parent() : $tourComponent,]) }}"
                                               class="btn btn-outline-success btn-sm mb-1" title="Upgrade">{{ Icon::upgrade() }}</a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                    {{ Icon::upgrade() }}
                                                </span>
                                        @endif
                                        <a href="{{ route('transport-inventory-tours.edit', ['tour' => $tour, 'transportInventoryTour' => $tourComponent,]) }}"
                                           class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                                    @else
                                        <span class="btn btn-outline-dark btn-sm mb-1">
                                                    {{ Icon::upgrade() }}
                                            </span>
                                        <span class="btn btn-outline-dark btn-sm mb-1">
                                                {{ Icon::edit() }}
                                            </span>
                                    @endcan
                                    @can('delete', TransportInventoryTour::class)
                                        @if($tourComponent->is_bookable)
                                            <a href="#"
                                               onclick="$('#transport-{{$tourComponent->id}}-delete').submit()"
                                               class="btn btn-outline-danger btn-sm mb-1" title="Delete">{{ Icon::delete() }}</a>
                                            <form action="{{ route('transport-inventory-tours.delete', ['tour' => $tour, 'transportInventoryTour' => $tourComponent,]) }}"
                                                  method="post" id="transport-{{$tourComponent->id}}-delete">
                                                @csrf
                                            </form>
                                        @else
                                            <a href="#"
                                               onclick="$('#transport-{{$tourComponent->id}}-restore').submit()"
                                               class="btn btn-outline-warning btn-sm mb-1">{{ Icon::bookable() }}</a>
                                            <form action="{{ route('transport-inventory-tours.restore', ['tour' => $tour, 'transportInventoryTour' => $tourComponent,]) }}"
                                                  method="post" id="transport-{{$tourComponent->id}}-restore">
                                                @csrf
                                            </form>
                                        @endif
                                    @else
                                        <span class="btn btn-outline-dark btn-sm mb-1">
                                                {{ Icon::delete() }}
                                            </span>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </x-admin.section.card>
    {{-- Payment Installment Section --}}
    <hr class="splitter"/>
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Room Availability</h2>
    </div>
    <x-admin.section.card>
        <table id="templates-table" class="datatable table table-striped">
            <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Template</th>
                <th scope="col">Available</th>
            </tr>
            </thead>
            @foreach($tour->getAccommodationTemplateData() as $templateData)
                <tr>
                    <th scope="row">{{ f_date($templateData['template']->inventory->check_in->clone()->setTime(0,0,0)) }}</th>
                    <td>{{ $templateData['template'] }}</td>
                    <td>{{ implode(', ', $templateData['available']) }}</td>
                </tr>
            @endforeach
        </table>
    </x-admin.section.card>
    {{-- Payment Installment Section --}}
    <hr class="splitter"/>
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Payment Installments</h2>
    </div>
    <x-admin.section.card>
        <div class="text-end">
            <a href="{{ route('payment-installments.create', ['tour' => $tour,]) }}" class="btn btn-primary">
                {{ Icon::create() }}
                <span>Create</span>
            </a>
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <table id="installments-table" class="datatable table table-striped">
            <thead>
            <tr>
                <th scope="col">Type</th>
                <th scope="col">Due Date</th>
                <th scope="col">Amount Due</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tr>
                <th scope="row">Deposit</th>
                <td>With Order</td>
                <td>{{ f_currency($tour->deposit_amount) }} ({{ $tour->deposit_percentage }}%)</td>
                <td>
                    <a href="{{route('tours.edit', ['tour' => $tour,])}}"
                       class="btn btn-outline-success btn-sm mb-1">
                        {{ Icon::edit() }}
                    </a>
                </td>
            </tr>
            @foreach($tour->paymentInstallments as $installment)
                <tr>
                    <th scope="row">Installment</th>
                    <td>{{ f_date($installment->due_on) }}</td>
                    <td>{{ f_currency($installment->cost) }} ({{ $installment->percentage }}%)</td>
                    <td class="actions">

                        <a href="{{route('payment-installments.edit', ['tour' => $tour, 'paymentInstallment' => $installment,])}}"
                           class="btn btn-outline-success btn-sm mb-1">
                            {{ Icon::edit() }}
                        </a>
                        <a href="#" class="btn btn-outline-danger btn-sm mb-1"
                           onclick="event.preventDefault();document.getElementById('paymentInstallment-{{ $installment->id }}-delete').submit();">
                            {{ Icon::delete() }}
                        </a>
                        <form id="paymentInstallment-{{ $installment->id }}-delete"
                              action="{{ route('payment-installments.delete', ['tour' => $tour, 'paymentInstallment' => $installment,]) }}"
                              method="POST" style="display: none;">{{ csrf_field() }}</form>
                    </td>
                </tr>
            @endforeach
            <tr>
                <th scope="row">Remaining Balance</th>
                <td>{{ f_date($tour->final_payment) }}</td>
                <td>{{ f_currency($tour->remaining_installment) }} ({{ $tour->remaining_percentage }}%)</td>
                <td>
                    <a href="{{route('tours.edit', ['tour' => $tour,])}}"
                       class="btn btn-outline-success btn-sm mb-1" title="Edit">
                        {{ Icon::edit() }}
                    </a>
                </td>
            </tr>
        </table>
    </x-admin.section.card>
    {{-- Merchandise Section --}}
    <hr class="splitter"/>
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Merchandise</h2>
    </div>
    <x-admin.section.card>
        <table id="merchandise-table" class="datatable table table-striped table-responsive-sm">
            <thead>
            <tr>
                <th scope="col">Icon</th>
                <th scope="col">Name</th>
                <th scope="col">Variant</th>
                <th scope="col">Size</th>
                <th scope="col">Component Type</th>
                <th scope="col">Stock Controlled?</th>
                <th scope="col">Sales Price</th>
                <th scope="col">Stock</th>
                <th scope="col">Notes</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($tour->merchandise as $merchandise)
                <tr>
                    <td><img src="{{ $merchandise->inventory->asset }}" class="image tiny"/></td>
                    <td style="min-width: 100px">{{ $merchandise->inventory->component->name }}</td>
                    <td>{{ $merchandise->inventory->variant->name }}</td>
                    <td>{{ $merchandise->inventory->size?->name ?? 'No Size'  }}</td>
                    <td>{{ $merchandise->stock_control_active }}</td>
                    <td>{{ f_currency($merchandise->tour_sales_price) }}</td>
                    <td>
                        {{ $merchandise->repository->getUsedStock() }}
                        /{{ $merchandise->repository->getTotalStock() }}<br/>
                        ({{$merchandise->repository->getAvailableStock()}} Available)
                    </td>
                    <td>{{ $merchandise->internal_notes }}</td>
                    <td class="actions">
                        @can('update', Merchandise::class)
                            <a href="{{ route('merchandise.inventory.tour.edit', ['tour' => $tour, 'inventoryTour' => $merchandise,]) }}"
                               class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1" title="Delete">
                                            {{ Icon::delete() }}
                                        </span>
                        @endcan
                        @can('delete', Merchandise::class)
                            <a href="#" title="Delete" onclick="$('#merchandise-{{$merchandise->id}}-delete').submit()"
                               class="btn btn-outline-{{ $merchandise->is_bookable ? 'danger' : 'warning' }} btn-sm mb-1">
                                {{ $merchandise->is_bookable ? Icon::delete() : Icon::enable() }}
                            </a>
                            <form action="{{ route($merchandise->is_bookable ? 'merchandise.inventory.tour.delete' : 'merchandise.inventory.tour.restore',['tour' => $tour, 'inventoryTour' => $merchandise,]) }}"
                                  method="post" id="merchandise-{{$merchandise->id}}-delete">
                                @csrf
                            </form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1" title="Delete">
                                    {{ Icon::delete() }}
                                </span>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </x-admin.section.card>
    <hr class="splitter"/>
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Orders</h2>
    </div>
    <x-admin.section.card>
        <table id="orders-table" class="datatable table table-striped">
            <thead>
            <tr>
                <th scope="col">Booking Reference</th>
                <th scope="col">Lead Booker</th>
                <th scope="col">Customers</th>
                <th scope="col">Order Status</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($tour->orders as $order)
                <tr>
                    <th scope="row"><a href="{{route('orders.view', ['order' => $order,])}}"
                                       class="link link-primary">{{ $order->booking_reference }}</a></th>
                    <td>{{ $order->leadBooker->customer->first_name . ' ' . $order->leadBooker->customer->last_name }}</td>
                    <td>{{ sizeof($order->orderCustomers) }}</td>
                    <td>
                        <h6 class="badge badge-{{ $order->status->color() }} fw-bold">{{ $order->status->description() }}</h6>
                    </td>
                    <td class="actions">
                        <a href="{{route('orders.edit', ['order' => $order,])}}"
                           class="btn btn-outline-success btn-sm mb-1" title="Edit">
                            {{ Icon::edit() }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </x-admin.section.card>
    <hr class="splitter"/>
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Terms and Conditions</h2>
    </div>
    <x-admin.section.card>
        {!! $tour->terms !!}
    </x-admin.section.card>

@endsection
