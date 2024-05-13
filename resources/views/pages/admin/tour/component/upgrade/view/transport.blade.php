@extends('layout.master')

@section('title', 'View Transport Inventory Upgrades')

@section('content')
    @include('pages.upgrades.view.header')
    {{-- Upgrades Section --}}
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Included</h2>
    </div>
    <div class="otm-callout">
        <div class="row">
            <div class="col-12">
                <h4 class="fw-bold">{{ $inventoryTour->transportInventory->transport->name }}</h4>
            </div>
            <div class="col-12 col-xl-3">
                <p>Transport Type</p>
                <h6 class="fw-bold">{{ $inventoryTour->transportInventory->transport->transportType->name }}</h6>
            </div>
            <div class="col-12 col-xl-3">
                <p>Travel Class</p>
                <h6 class="fw-bold">{{ $inventoryTour->transportInventory->travelClass->name }}</h6>
            </div>
            <div class="col-12 col-xl-3">
                <p>Departs At</p>
                <h6 class="fw-bold">{{ $inventoryTour->transportInventory->departs_at }}</h6>
            </div>
            <div class="col-12 col-xl-3">
                <p>Arrives At</p>
                <h6 class="fw-bold">{{ $inventoryTour->transportInventory->arrives_at }}</h6>
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
            <a href="{{ route('transport-upgrade.create', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]) }}" class="btn btn-primary">
                {{ Icon::create() }}
                <span>Create</span>
            </a>
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <div id="tables" class="tab-content otm-tab-content">
            {{-- Transport Table --}}
            <div id="transport" role="tabpanel" class="tab-pane fade show active">
                <div id="transport-details">
                    <table id="transport-table" class="datatable table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Travel Class</th>
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
                                <td>{{ $upgrade->upgrade->transportInventory->transport->name }}</td>
                                <td>{{ $upgrade->upgrade->transportInventory->travelClass->name }}</td>
                                <td>
                                    {{ f_datetime($upgrade->upgrade->transportInventory->departs_at) }}
                                    <input type="checkbox" disabled
                                           @if($inventoryTour->departure_time_confirmed == 1) checked @endif>
                                </td>
                                <td>
                                    {{ f_datetime($upgrade->upgrade->transportInventory->arrives_at) }}
                                    <input type="checkbox" disabled
                                           @if($inventoryTour->arrival_time_confirmed == 1) checked @endif>
                                </td>
                                <td>{{ $upgrade->upgrade->transportInventory->stock }}</td>
                                <td>{{ $upgrade->description }}</td>
                                <td>{{ f_currency($upgrade->upgrade->tour_sales_price) }}</td>
                                <td class="actions">
                                    @can('update', \App\Models\Transport\TransportInventoryTour::class)
                                        <a href="{{ route('transport-upgrade.edit', ['tour' => $tour, 'inventoryTour' => $inventoryTour,'upgrade'=>$upgrade]) }}"
                                           class="btn btn-outline-primary btn-sm mb-1">{{ Icon::edit() }}</a>
                                    @else
                                        <span class="btn btn-outline-dark btn-sm mb-1">
                                                    {{ Icon::edit() }}
                                                </span>
                                    @endcan
                                    @can('delete', \App\Models\Transport\TransportInventoryTour::class)
                                        <a href="#"
                                           onclick="$('#transport-{{$upgrade->upgrade->id}}-delete').submit()"
                                           class="btn btn-outline-danger btn-sm mb-1">{{ Icon::delete() }}</a>
                                        <form action="{{ route('transport-upgrade.delete', ['tour' => $tour, 'inventoryTour' => $inventoryTour, 'upgrade' => $upgrade]) }}"
                                              method="post" id="transport-{{$upgrade->upgrade->id}}-delete">
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
