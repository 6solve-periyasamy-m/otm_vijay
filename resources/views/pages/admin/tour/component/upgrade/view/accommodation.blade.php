@extends('layout.master')

@section('title', 'View Accommodation Inventory Upgrades')

@section('content')
    @include('pages.upgrades.view.header')
    {{-- Upgrades Section --}}
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Included</h2>
    </div>
    <div class="otm-callout">
        <div class="row">
            <div class="col-12">
                <h4 class="fw-bold">{{ $inventoryTour->accommodationInventory->accommodation->name }}</h4>
            </div>
            <div class="col-12 col-xl-3">
                <p>Room Type</p>
                <h6 class="fw-bold">{{ $inventoryTour->accommodationInventory->roomType->name }}</h6>
            </div>
            <div class="col-12 col-xl-3">
                <p>Board Type</p>
                <h6 class="fw-bold">{{ $inventoryTour->accommodationInventory->boardType->name }}</h6>
            </div>
            <div class="col-12 col-xl-3">
                <p>Check In</p>
                <h6 class="fw-bold">{{ $inventoryTour->accommodationInventory->check_in }}</h6>
            </div>
            <div class="col-12 col-xl-3">
                <p>Check Out</p>
                <h6 class="fw-bold">{{ $inventoryTour->accommodationInventory->check_out }}</h6>
            </div>
        </div>
    </div>
    <hr class="splitter"/>
    {{-- Upgrades Section --}}
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Upgrades</h2>
    </div>
    <div class="card">
        <div class="card-body text-end">
            <a href="{{ route('accommodation-upgrade.create', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]) }}" class="btn btn-primary">
                {{ Icon::create() }}
                <span>Create</span>
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
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
                                <th scope="col">Stock</th>
                                <th scope="col">Description</th>
                                <th scope="col">Upgrade Price</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($inventoryTour->upgrades as $upgrade)
                                <tr>
                                    <td style="min-width: 200px">{{ f_datetime($upgrade->upgrade->accommodationInventory->check_in) }}
                                        to {{ f_datetime($upgrade->upgrade->accommodationInventory->check_out) }}</td>
                                    <td>{{ $upgrade->upgrade->accommodationInventory->accommodation->name }}</td>
                                    <td>{{ $upgrade->upgrade->accommodationInventory->roomType->name }}</td>
                                    <td>{{ $upgrade->upgrade->accommodationInventory->boardType->name }}</td>
                                    <td>{{ $upgrade->upgrade->accommodationInventory->stock }}</td>
                                    <td>{{ $upgrade->description }}</td>
                                    <td>{{ f_currency($upgrade->upgrade->tour_sales_price) }}</td>
                                    <td class="actions">
                                        @can('update', \App\Models\Accommodation\AccommodationInventoryTour::class)
                                            <a href="{{ route('accommodation-upgrade.edit', ['tour' => $tour, 'inventoryTour' => $inventoryTour,'upgrade'=>$upgrade]) }}"
                                               class="btn btn-outline-primary btn-sm mb-1">{{ Icon::edit() }}</a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                    {{ Icon::edit() }}
                                                </span>
                                        @endcan
                                        @can('delete', \App\Models\Accommodation\AccommodationInventoryTour::class)
                                            <a href="#"
                                               onclick="$('#accommodation-{{$upgrade->upgrade->id}}-delete').submit()"
                                               class="btn btn-outline-danger btn-sm mb-1">{{ Icon::delete() }}</a>
                                            <form action="{{ route('accommodation-upgrade.delete', ['tour' => $tour, 'inventoryTour' => $inventoryTour, 'upgrade' => $upgrade]) }}"
                                                  method="post" id="accommodation-{{$upgrade->upgrade->id}}-delete">
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
        </div>
    </div>
@endsection
