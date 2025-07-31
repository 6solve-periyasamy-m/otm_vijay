@php
    /** @var \App\Models\Flight\Flight $flight */
@endphp

@extends('layout.component')

@section('title', 'View Flight')

@section('info')
    <div class="otm-callout">
        <div class="row">
            @if(isset($flight->image_url))
                <div class="col-2">
                    <img src="{{ asset($flight->image_url) }}" class="img-thumbnail image large">
                </div>
            @endif
            <div class="col-{{ isset($flight->image_url) ? 10 : 12 }} row">
                <div class="col-12">
                    <h4 class="fw-bold">{{ $flight->airline->name }}</h4>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Departure Airport</p>
                    <h6 class="fw-bold">{{ $flight->departureAirport }}</h6>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Arrival Airport</p>
                    <h6 class="fw-bold">{{ $flight->arrivalAirport }}</h6>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Is Domestic</p>
                    <h6 class="fw-bold">{{ $flight->is_domestic ? "Domestic" : "International" }}</h6>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Currency</p>
                    <h6 class="fw-bold">{{ $flight->currency }}</h6>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Internal Notes</p>
                    <h6 class="fw-bold">{{ $flight->internal_notes }}</h6>
                </div>

                <div class="col-12">
                    @can('update', \App\Models\Flight\Flight::class)
                        <a class="btn btn-success" href="{{route('flights.edit', ['flight' => $flight,])}}">
                            {{ Icon::edit() }}
                            <span>Edit Flight</span>
                        </a>
                    @endcan
                    @can('create', \App\Models\Flight\Flight::class)
                        <a class="btn btn-info" title="Duplicate Flight" href="{{route('flights.duplicate', ['flight' => $flight,])}}">
                            {{ Icon::copy() }}
                            <span>Duplicate Flight</span>
                        </a>
                    @endcan
                    <a class="btn btn-secondary" href="{{route('flights.manifest.view', ['flight' => $flight,])}}">
                        {{ Icon::list() }}
                        <span>View Manifest</span>
                    </a>
                    @can('delete', \App\Models\Flight\Flight::class)
                        <a href="{{ route('flights.archive', ['flight' => $flight]) }}" title="{{ $flight->archived ? "Restore" : "Archive" }}" class="btn btn-{{ $flight->archived ? "warning" : "danger" }}">
                            {{ Icon::archive() }}
                            <span>{{ $flight->archived ? "Restore" : "Archive" }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('inventory')
    @can('create', \App\Models\Flight\FlightInventory::class)
        <x-admin.section.card>
            <a href="{{ route('flight-inventories.create', ['flight' => $flight, ]) }}"
               class="btn btn-primary float-end me-1">
                {{ Icon::create() }}
                <span>Add Inventory</span>
            </a>
        </x-admin.section.card>
    @endcan
    <x-admin.section.card>
        <table id="flightInventory" style="width: 100%;" class="datatable table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Flight Number</th>
                <th scope="col">Travel Class</th>
                <th scope="col">Check In Time</th>
                <th scope="col">Departure Time</th>
                <th scope="col">Arrival Time</th>
                <th scope="col">FIT Selectable</th>
                <th scope="col">Stock</th>
                <th scope="col">Contracted Stock</th>
                <th scope="col">Purchase Price</th>
                <th scope="col">Sales Price</th>
                <th scope="col">Internal Notes</th>
                <th scope="col">External Notes</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($flight->flightInventory as $inventory)
                <tr>
                    <td>{{ $inventory->flight_number }}</td>
                    <td>{{ $inventory->travelClass->name }}</td>
                    <td data-sort="{{$inventory->check_in?->unix()}}">{{ f_datetime($inventory->check_in) }}</td>
                    <td data-sort="{{$inventory->departs_at?->unix()}}">{{ f_datetime($inventory->departs_at) }}</td>
                    <td data-sort="{{$inventory->arrives_at?->unix()}}">{{ f_datetime($inventory->arrives_at) }}</td>
                    <td>
                        <input type="checkbox" disabled @if($inventory->fit_selectable == 1) checked @endif>
                    </td>
                    <td>
                        {{$inventory->stock - $inventory->used_stock}}/{{ $inventory->stock }}<br/>
                        ({{$inventory->used_stock}} Sold)
                    </td>
                    <td>{{ $inventory->contracted }}</td>
                    <td>{{ f_currency($inventory->purchase_price, $flight->currency) }}</td>
                    <td>{{ f_currency($inventory->sales_price) }}</td>
                    <td>{{ $inventory->internal_notes }}</td>
                    <td>{{ $inventory->external_notes }}</td>
                    <td class="actions-4">
                        @can('read', \App\Models\Flight\FlightInventory::class)
                            <a href="{{route('flight-inventories.manifest.view', ['flight' => $flight, 'inventory' => $inventory,])}}"
                               class="btn btn-outline-secondary btn-sm mb-1" title="List">
                                {{ Icon::list() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::list() }}
                            </span>
                        @endcan
                        @can('create', \App\Models\Flight\FlightInventory::class)
                            <a href="{{route('flight-inventories.duplicate', ['flight' => $flight, 'inventory' => $inventory,])}}"
                               class="btn btn-outline-blue btn-sm mb-1" title="Copy">
                                {{ Icon::copy() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::copy() }}
                            </span>
                        @endcan
                        @can('update', \App\Models\Flight\FlightInventory::class)
                            <a href="{{route('flight-inventories.edit', ['flight' => $flight, 'inventory' => $inventory,])}}"
                               class="btn btn-outline-success btn-sm mb-1" title="Edit">
                                {{ Icon::edit() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::edit() }}
                            </span>
                        @endcan
                        @can('delete', \App\Models\Flight\FlightInventory::class)
                            <a href="#" title="Delete" class="btn btn-outline-danger btn-sm mb-1"
                               onclick="event.preventDefault();document.getElementById('flightInventory-{{ $inventory->id }}-delete').submit();">
                                {{ Icon::delete() }}
                            </a>
                            <form id="flightInventory-{{ $inventory->id }}-delete"
                                  action="{{ route('flight-inventories.delete', ['flight' => $flight, 'inventory' => $inventory,]) }}"
                                  method="POST"
                                  style="display: none;">{{ csrf_field() }}</form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::delete() }}
                            </span>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </x-admin.section.card>
@endsection
