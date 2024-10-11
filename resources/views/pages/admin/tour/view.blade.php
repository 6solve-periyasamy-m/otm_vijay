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
                    @if($tour->getBookingFormUrl() !== null)
                        <a target="_blank" href="{{ $tour->getBookingFormUrl() }}">{{ $tour->getBookingFormUrl() }}</a>
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
                <h6 class="fw-bold">{{ sigfig($tour->repository->getCosting()->getBaseMargin()) }}%</h6>
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
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#components">
                    {{ Icon::list() }}
                    All Components
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#accommodation">
                    {{ Icon::accommodation() }}
                    Accommodation
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities">
                    {{ Icon::activity() }}
                    Activities
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#flights">
                    {{ Icon::flight() }}
                    Flights
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transports">
                    {{ Icon::transport() }}
                    Transport
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#merchandise">
                    {{ Icon::merchandise() }}
                    Merchandise
                </button>
            </li>
        </ul>
        {{-- Tables Definition --}}
        <div id="tables" class="tab-content otm-tab-content">
            {{-- All Components --}}
            @include('partials.admin.tour.component.all', ['tour' => $tour])
            {{-- Accommodation Table --}}
            @include('partials.admin.tour.component.accommodation', ['tour' => $tour])
            {{-- Activities Table --}}
            @include('partials.admin.tour.component.activity', ['tour' => $tour])
            {{-- Flights Table --}}
            @include('partials.admin.tour.component.flight', ['tour' => $tour])
            {{-- Transports Table --}}
            @include('partials.admin.tour.component.transport', ['tour' => $tour])
            {{-- Merchandise Table --}}
            @include('partials.admin.tour.component.merchandise', ['tour' => $tour])
        </div>
    </x-admin.section.card>
    <hr class="splitter"/>
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
    <x-admin.section.accordion closed>
        <x-slot:title>Room Availability</x-slot:title>
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
    </x-admin.section.accordion>
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
                    <td>{{ $order->leadBooker?->customer?->first_name . ' ' . $order->leadBooker?->customer?->last_name }}</td>
                    <td>{{ $order->orderCustomers()->count() }}</td>
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
