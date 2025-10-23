@php
    /** @var \App\Models\Transport\Transport $transport */
@endphp

@extends('layout.component')

@section('title', 'View transport')

@section('info')
    <div class="otm-callout">
        <div class="row">
            @if(isset($transport->image_url))
                <div class="col-2">
                    <img src="{{ asset($transport->image_url) }}" class="img-thumbnail image large">
                </div>
            @endif
            <div class="col-{{ isset($transport->image_url) ? 10 : 12 }} row">
                <div class="col-12 text-capitalize">
                    <h4 class="fw-bold">{{ $transport->name }}</h4>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Transport Type</p>
                    <h6 class="fw-bold">{{ $transport->transportType->name }}</h6>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Operator</p>
                    <h6 class="fw-bold">{{ $transport->operator->name }}</h6>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Departure Location</p>
                    <h6 class="fw-bold">{{ $transport->departureAddress}}</h6>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Arrival Location</p>
                    <h6 class="fw-bold">{{ $transport->arrivalAddress }}</h6>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Currency</p>
                    <h6 class="fw-bold">{{ $transport->currency }}</h6>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Description</p>
                    <h6 class="fw-bold">{{ $transport->description }}</h6>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Internal Notes</p>
                    <h6 class="fw-bold">{{ $transport->internal_notes }}</h6>
                </div>
                <div class="col-12">
                    @can('self-update', \App\Models\Transport\Transport::class)
                        <a class="btn btn-success" href="{{route('transports.edit', ['transport' => $transport,])}}">
                            {{ Icon::edit() }}
                            <span>Edit Transport</span>
                        </a>
                    @endcan
                    @can('update', \App\Models\Transport\Transport::class)
                        <a class="btn btn-info" title="Duplicate With Inventory" href="{{route('transports.duplicate', ['transport' => $transport,])}}">
                            {{ Icon::copy() }}
                            <span>Duplicate With Inventory</span>
                        </a>
                        <a class="btn btn-info" title="Duplicate Without Inventory" href="{{route('transports.duplicate', ['transport' => $transport, 'inventory' => false,])}}">
                            {{ Icon::copy() }}
                            <span>Duplicate Without Inventory</span>
                        </a>
                    @endcan
                    <a class="btn btn-secondary" href="{{route('transports.manifest.view', ['transport' => $transport,])}}">
                        {{ Icon::list() }}
                        <span>View Manifest</span>
                    </a>
                    @can('delete', \App\Models\Transport\Transport::class)
                        <a href="{{ route('transports.archive', ['transport' => $transport]) }}" title="{{ $transport->archived ? "Restore" : "Archive" }}" class="btn btn-{{ $transport->archived ? "warning" : "danger" }}">
                            {{ Icon::archive() }}
                            <span>{{ $transport->archived ? "Restore" : "Archive" }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('inventory')
    @if(Gate::check('self-child-access', [$transport, \App\Models\Transport\TransportInventory::class]) || Gate::check('create', \App\Models\Transport\TransportInventory::class))
        <x-admin.section.card>
            <a href="{{ route('transport-inventories.create', ['transport' => $transport, ]) }}"
               class="btn btn-primary float-end me-1">
                {{ Icon::create() }}
                <span>Add Inventory</span>
            </a>
        </x-admin.section.card>
    @endif
    <x-admin.section.card>
        <table id="transportInventory" style="width: 100%;" class="datatable table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Travel Class</th>
                <th scope="col">Transport Number</th>
                <th scope="col">Departure Date Time</th>
                <th scope="col">Arrival Date Time</th>
                <th scope="col">FIT Selectable</th>
                <th scope="col">Stock</th>
                <th scope="col">Contracted Stock</th>
                <th scope="col">Purchase Price</th>
                <th scope="col">Sales Price</th>
                <th scope="col">Occupancy</th>                
                <th scope="col">Internal Notes</th>
                <th scope="col">External Notes</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($transport->transportInventory as $inventory)
                @php
                    $occupancy = $inventory->transportOccupancy;
                    $name = $occupancy?->name;
                    $maxOccupancy = $occupancy?->maximum_occupancy;
                @endphp
                <tr>
                    <td>{{ $inventory->travelClass->name }}</td>
                    <td>{{ $inventory->transport_number ?? 'Not Set' }}</td>
                    <td data-sort="{{$inventory->departs_at?->unix()}}">
                        {{ f_datetime($inventory->departs_at) }}&nbsp
                        <input type="checkbox" disabled
                               @if($inventory->departure_time_confirmed == 1) checked @endif>
                    </td>
                    <td data-sort="{{$inventory->arrives_at?->unix()}}">
                        {{ f_datetime($inventory->arrives_at) }}
                        <input type="checkbox" disabled
                               @if($inventory->arrival_time_confirmed == 1) checked @endif>
                    </td>
                    <td>
                        <input type="checkbox" disabled @if($inventory->fit_selectable == 1) checked @endif>
                    </td>
                    <td>
                        {{$inventory->stock - $inventory->used_stock}}
                        /{{ $inventory->stock }}<br/>
                        ({{$inventory->used_stock}} Sold)
                    </td>
                    <td>{{ $inventory->contracted }}</td>
                    <td>{{ f_currency($inventory->purchase_price, $inventory->repository->getCurrency()) }}</td>
                    <td>{{ f_currency($inventory->sales_price) }}</td>
                    <td>
                        @if ($name)
                            {{ $name }}@if ($maxOccupancy) ({{ $maxOccupancy }}) @endif
                        @endif
                    </td>
                    <td>{{ $inventory->internal_notes }}</td>
                    <td>{{ $inventory->external_notes }}</td>
                    <td class="actions-4">
                        @can('read', \App\Models\Transport\TransportInventory::class)
                            <a href="{{route('transport-inventories.manifest.view', ['transport' => $transport, 'inventory' => $inventory,])}}"
                               class="btn btn-outline-secondary btn-sm mb-1" title="Manifest">
                                {{ Icon::list() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1"  title="Manifest">
                                {{ Icon::list() }}
                            </span>
                        @endcan
                        @can('self-child-access', [$transport, \App\Models\Transport\TransportInventory::class])
                            <a href="{{route('transport-inventories.duplicate', ['transport' => $transport, 'inventory' => $inventory,])}}"
                               class="btn btn-outline-blue btn-sm mb-1"  title="Copy">
                                {{ Icon::copy() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1"  title="Copy">
                            {{ Icon::copy() }}
                        </span>
                        @endcan
                        @can('self-update', \App\Models\Transport\TransportInventory::class)
                            <a href="{{route('transport-inventories.edit', ['transport' => $transport, 'inventory' => $inventory,])}}"
                               class="btn btn-sm btn-outline-success mb-1"  title="Edit">
                                {{ Icon::edit() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1"  title="Edit">
                            {{ Icon::edit() }}
                        </span>
                        @endcan
                        @can('self-delete', \App\Models\Transport\TransportInventory::class)
                            <a href="#" class="btn btn-sm btn-outline-danger mb-1"  title="Delete"
                               onclick="event.preventDefault();document.getElementById('transportInventory-{{ $inventory->id }}-delete').submit();">
                                {{ Icon::delete() }}
                            </a>
                            <form id="transportInventory-{{ $inventory->id }}-delete"
                                  action="{{ route('transport-inventories.delete', ['transport' => $transport, 'inventory' => $inventory,]) }}"
                                  method="POST" style="display: none;">{{ csrf_field() }}</form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1"  title="Delete">
                            {{ Icon::delete() }}
                        </span>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </x-admin.section.card>
@endsection
