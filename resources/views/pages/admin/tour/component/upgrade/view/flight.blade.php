@extends('layout.master')

@section('title', 'View Flight Inventory Upgrades')

@section('content')
    @include('pages.admin.tour.component.upgrade.view.header')
    {{-- Upgrades Section --}}
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Included</h2>
    </div>
    <div class="otm-callout">
        <div class="row">
            <div class="col-12">
                <h4 class="fw-bold">{{ $inventoryTour->flightInventory->flight_number }}</h4>
            </div>
            <div class="col-12 col-xl-3">
                <p>Airline</p>
                <h6 class="fw-bold">{{ $inventoryTour->flightInventory->flight->airline->name }}</h6>
            </div>
            <div class="col-12 col-xl-3">
                <p>Travel Class</p>
                <h6 class="fw-bold">{{ $inventoryTour->flightInventory->travelClass->name }}</h6>
            </div>
            <div class="col-12 col-xl-3">
                <p>Check In</p>
                <h6 class="fw-bold">{{ f_datetime($inventoryTour->flightInventory->check_in) }}</h6>
            </div>
            <div class="col-12 col-xl-3">
                <p>Arrives At</p>
                <h6 class="fw-bold">{{ f_datetime($inventoryTour->flightInventory->arrives_at) }}</h6>
            </div>
        </div>
    </div>
    <hr class="splitter"/>
    {{-- Upgrades Section --}}
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Upgrades</h2>
    </div>
    <x-admin.section.card>
        <div class="text-end">
            <a href="{{ route('flight-upgrade.create', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]) }}" class="btn btn-primary">
                {{ Icon::create() }}
                <span>Create</span>
            </a>
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <div id="tables" class="tab-content otm-tab-content">
            {{-- Flight Table --}}
            <div id="flight" role="tabpanel" class="tab-pane fade show active">
                <div id="flight-details">
                    <table id="flight-table" class="table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Flight Number</th>
                            <th scope="col">Travel Class</th>
                            <th scope="col">Check In</th>
                            <th scope="col">Departs At</th>
                            <th scope="col">Arrives At</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Description</th>
                            <th scope="col">Upgrade Price</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        @foreach($inventoryTour->upgrades as $upgrade)
                            <tr>
                                <td>{{ $upgrade->upgrade->flightInventory->flight_number }}</td>
                                <td>{{ $upgrade->upgrade->flightInventory->travelClass->name }}</td>
                                <td>{{ $upgrade->upgrade->flightInventory->check_in }}</td>
                                <td>{{ $upgrade->upgrade->flightInventory->departs_at }}</td>
                                <td>{{ $upgrade->upgrade->flightInventory->arrives_at }}</td>
                                <td>{{ $upgrade->upgrade->flightInventory->stock }}</td>
                                <td>{{ $upgrade->description }}</td>
                                <td>{{ f_currency($upgrade->upgrade->tour_sales_price) }}</td>
                                <td class="actions">
                                    @can('update', \App\Models\Flight\FlightInventoryTour::class)
                                        <a href="{{ route('flight-upgrade.edit', ['tour' => $tour, 'inventoryTour' => $inventoryTour,'upgrade'=>$upgrade]) }}"
                                           class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                                    @else
                                        <span class="btn btn-outline-dark btn-sm mb-1">
                                                    {{ Icon::edit() }}
                                                </span>
                                    @endcan
                                    @can('delete', \App\Models\Flight\FlightInventoryTour::class)
                                        <a href="#" onclick="$('#flight-{{$upgrade->upgrade->id}}-delete').submit()"
                                           class="btn btn-outline-danger btn-sm mb-1" title="Delete">{{ Icon::delete() }}</a>
                                        <form action="{{ route('flight-upgrade.delete', ['tour' => $tour, 'inventoryTour' => $inventoryTour, 'upgrade' => $upgrade]) }}"
                                              method="post" id="flight-{{$upgrade->upgrade->id}}-delete">
                                            @csrf
                                        </form>
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
@endsection
