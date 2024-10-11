@php /** @var \App\Models\Tour\Tour $tour */ @endphp
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
                    <th scope="col">Ordered</th>
                    <th scope="col">Bookable?</th>
                    <th scope="col">Stock Controlled?</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            @foreach($tour->activityInventoryTours as $tourComponent)
                <tr>
                    <td style="min-width: 200px" data-sort="{{$tourComponent->inventory->starts_at?->unix()}}">
                        {{ f_datetime($tourComponent->inventory->starts_at) }}
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
                    <td>{{ $tourComponent->repository->getOrderedCount() }} Ordered, {{ $tourComponent->repository->getBookedCount() }} <abbr title="Bookings created through the form. Will include ones converted to orders">Booked</abbr></td>
                    <td>{{ f_bool($tourComponent->is_bookable) }}</td>
                    <td>{{ f_bool($tourComponent->stock_control_active) }}</td>
                    <td class="actions-3">
                        @can('update', \App\Models\Activity\ActivityInventoryTour::class)
                            @if($tourComponent->tour_component_type !== 'Add-on')
                                <a href="{{ route('activity-upgrade.view', ['tour' => $tour, 'inventoryTour' => $tourComponent->tour_component_type == 'Upgrade' ? $tourComponent->parent() : $tourComponent,]) }}"
                                   class="btn btn-outline-success btn-sm mb-1" title="Upgrade">{{ Icon::upgrade() }}</a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1" title="Upgrade">
                                                    {{ Icon::upgrade() }}
                                                </span>
                            @endif
                            <a href="{{ route('activity-inventory-tours.edit', ['tour' => $tour, 'inventoryTour' => $tourComponent,]) }}"
                               class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1" title="Upgrade">
                                                    {{ Icon::upgrade() }}
                                                </span>
                            <span class="btn btn-outline-dark btn-sm mb-1" title="Edit">
                                                {{ Icon::edit() }}
                                            </span>
                        @endcan
                        @if(Bouncer::can('delete', \App\Models\Activity\ActivityInventoryTour::class) && $tourComponent->repository->canDelete())
                            <a href="#" title="Delete" onclick="$('#activity-{{$tourComponent->id}}-delete').submit()"
                               class="btn btn-outline-danger btn-sm mb-1">
                                {{ Icon::delete() }}
                            </a>
                            <form action="{{ route('activity-inventory-tours.delete', ['tour' => $tour, 'inventoryTour' => $tourComponent,]) }}" method="post" id="activity-{{$tourComponent->id}}-delete">
                                @csrf
                            </form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1" title="{{ Bouncer::can('delete', \App\Models\Activity\ActivityInventoryTour::class) ? 'Has Dependants' : 'Insufficient Permissions' }}">
                                {{ Icon::delete() }}
                            </span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
