@extends('layout.master')

@section('title', 'View Transport Inventory Upgrades')

@section('header-script')
    <script>
        $(document).ready( function () {
            $('#transport-table').DataTable({fixedHeader: true});
        });
    </script>
@endsection

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
    <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
    {{-- Upgrades Section --}}
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Upgrades</h2>
    </div>
    <div class="card">
        <div class="card-body text-end">
            <a href="{{ route('transport-upgrade.create', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]) }}" class="btn btn-primary">
                <i class="icon-plus"></i>
                <span>Create</span>
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div id="tables" class="tab-content otm-tab-content">
                {{-- Transport Table --}}
                <div id="transport" role="tabpanel" class="tab-pane fade show active">
                    <div id="transport-details">
                        <table id="transport-table" class="table table-striped table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Travel Class</th>
                                <th scope="col">Departs At</th>
                                <th scope="col">Arrives At</th>
                                <th scope="col">Stock</th>
                                <th scope="col">Upgrade Price</th>
                                <th scope="col">Description</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($inventoryTour->upgrades as $upgrade)
                                <tr>
                                    <td>{{ $upgrade->upgrade->transportInventory->transport->name }}</td>
                                    <td>{{ $upgrade->upgrade->transportInventory->travelClass->name }}</td>
                                    <td>
                                        {{ StringFormatter::formatDateTime($upgrade->upgrade->transportInventory->departs_at) }}
                                        <input type="checkbox" disabled @if($transportInventory->departure_time_confirmed == 1) checked @endif>
                                    </td>
                                    <td>
                                        {{ StringFormatter::formatDateTime($upgrade->upgrade->transportInventory->arrives_at) }}
                                        <input type="checkbox" disabled @if($transportInventory->arrival_time_confirmed == 1) checked @endif>
                                    </td>
                                    <td>{{ $upgrade->upgrade->transportInventory->stock }}</td>
                                    <td>{{ StringFormatter::formatCurrency($upgrade->upgrade->tour_sales_price) }}</td>
                                    <td class="actions">
                                        @can('update', \App\Models\TransportInventoryTour::class)
                                            <a href="{{ route('transport-upgrade.edit', ['tour' => $tour, 'inventoryTour' => $inventoryTour,'upgrade'=>$upgrade]) }}" class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                    <i class="icon-note"></i>
                                                </span>
                                        @endcan
                                        @can('delete', \App\Models\TransportInventoryTour::class)
                                            <a href="#" onclick="$('#transport-{{$upgrade->upgrade->id}}-delete').submit()" class="btn btn-outline-danger btn-sm mb-1"><i class="icon-trash"></i></a>
                                            <form action="{{ route('transport-inventory-tours.delete', ['tour' => $tour, 'transportInventoryTour' => $upgrade->upgrade->id,]) }}" method="post" id="transport-{{$upgrade->upgrade->id}}-delete">
                                                @csrf
                                            </form>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                    <i class="icon-trash"></i>
                                                </span>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
