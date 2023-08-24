@extends('layout.master')

@section('title', 'View Activity Inventory Upgrades')

@push('footer-stack')
    <script>
        $(document).ready( function () {
            $('#activity-table').DataTable({fixedHeader: true});
        });
    </script>
@endpush

@section('content')
    @include('pages.upgrades.view.header')
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
    <hr class="splitter"/>
    {{-- Upgrades Section --}}
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Upgrades</h2>
    </div>
    <div class="card">
        <div class="card-body text-end">
            <a href="{{ route('activity-upgrade.create', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]) }}" class="btn btn-primary">
                {{ Icon::create() }}
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
                                <th scope="col">Description</th>
                                <th scope="col">Upgrade Price</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($inventoryTour->upgrades as $upgrade)
                                <tr>
                                    <td style="min-width: 200px">{{ f_datetime($upgrade->upgrade->activityInventory->starts_at) }}
                                        to {{ f_datetime($upgrade->upgrade->activityInventory->ends_at) }}</td>
                                    <td>{{ $upgrade->upgrade->activityInventory->activity->name }}</td>
                                    <td>{{ $upgrade->upgrade->activityInventory->ticketType->name }}</td>
                                    <td>{{ $upgrade->upgrade->activityInventory->stock }}</td>
                                    <td>{{ $upgrade->description }}</td>
                                    <td>{{ f_currency($upgrade->upgrade->tour_sales_price) }}</td>
                                    <td class="actions">
                                        @can('update', \App\Models\Activity\ActivityInventoryTour::class)
                                            <a href="{{ route('activity-upgrade.edit', ['tour' => $tour, 'inventoryTour' => $inventoryTour,'upgrade'=>$upgrade]) }}"
                                               class="btn btn-outline-primary btn-sm mb-1">{{ Icon::edit() }}</a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                    {{ Icon::edit() }}
                                                </span>
                                        @endcan
                                        @can('delete', \App\Models\Activity\ActivityInventoryTour::class)
                                            <a href="#"
                                               onclick="$('#activity-{{$upgrade->upgrade->id}}-delete').submit()"
                                               class="btn btn-outline-danger btn-sm mb-1">{{ Icon::delete() }}</a>
                                            <form action="{{ route('activity-upgrade.delete', ['tour' => $tour, 'inventoryTour' => $inventoryTour, 'upgrade' => $upgrade]) }}"
                                                  method="post" id="activity-{{$upgrade->upgrade->id}}-delete">
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
