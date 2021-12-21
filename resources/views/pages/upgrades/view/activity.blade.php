@extends('layout.master')

@section('title', 'View Activity Inventory Upgrades')

@section('header-script')
    <script>
        $(document).ready( function () {
            $('#activity-table').DataTable({fixedHeader: true});
        });
    </script>
@endsection

@section('content')
    @include('pages.upgrades.pages.upgrades.view.header')
    {{-- Upgrades Section --}}
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Included</h2>
    </div>
    <div class="otm-callout">
        <div class="row">
            <div class="col-12">
                <h4 class="fw-bold">{{ $inventoryTour->activityInventory->activity->name }}</h4>
            </div>
            <div class="col-12 col-xl-3">
                <p>Activity Type</p>
                <h6 class="fw-bold">{{ $inventoryTour->activityInventory->activity->activityType->name }}</h6>
            </div>
            <div class="col-12 col-xl-3">
                <p>Ticket Type</p>
                <h6 class="fw-bold">{{ $inventoryTour->activityInventory->ticketType->name }}</h6>
            </div>
            <div class="col-12 col-xl-3">
                <p>Starts At</p>
                <h6 class="fw-bold">{{ $inventoryTour->activityInventory->starts_at }}</h6>
            </div>
            <div class="col-12 col-xl-3">
                <p>Ends At</p>
                <h6 class="fw-bold">{{ $inventoryTour->activityInventory->ends_at }}</h6>
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
            <a href="{{ route('activity-upgrade.create', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]) }}" class="btn btn-primary">
                <i class="icon-plus"></i>
                <span>Create</span>
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div id="tables" class="tab-content otm-tab-content">
                {{-- Activity Table --}}
                <div id="activity" role="tabpanel" class="tab-pane fade show active">
                    <div id="activity-details">
                        <table id="activity-table" class="table table-striped table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Ticket Type</th>
                                <th scope="col">Stock</th>
                                <th scope="col">Upgrade Price</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($inventoryTour->upgrades as $upgrade)
                                <tr>
                                    <td style="min-width: 200px">{{ StringFormatter::formatDateTime($upgrade->upgrade->activityInventory->starts_at) }} to {{ StringFormatter::formatDateTime($upgrade->upgrade->activityInventory->ends_at) }}</td>
                                    <td>{{ $upgrade->upgrade->activityInventory->activity->name }}</td>
                                    <td>{{ $upgrade->upgrade->activityInventory->ticketType->name }}</td>
                                    <td>{{ $upgrade->upgrade->activityInventory->stock }}</td>
                                    <td>{{ StringFormatter::formatCurrency($upgrade->upgrade->tour_sales_price) }}</td>
                                    <td class="actions">
                                        @can('update', \App\Models\ActivityInventoryTour::class)
                                            <a href="{{ route('activity-upgrade.edit', ['tour' => $tour, 'inventoryTour' => $inventoryTour,'upgrade'=>$upgrade]) }}" class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                    <i class="icon-note"></i>
                                                </span>
                                        @endcan
                                        @can('delete', \App\Models\ActivityInventoryTour::class)
                                            <a href="#" onclick="$('#activity-{{$upgrade->upgrade->id}}-delete').submit()" class="btn btn-outline-danger btn-sm mb-1"><i class="icon-trash"></i></a>
                                            <form action="{{ route('activity-inventory-tours.delete', ['tour' => $tour, 'activityInventoryTour' => $upgrade->upgrade->id,]) }}" method="post" id="activity-{{$upgrade->upgrade->id}}-delete">
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
