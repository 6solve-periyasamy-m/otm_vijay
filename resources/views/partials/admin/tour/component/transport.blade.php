@php /** @var \App\Models\Tour\Tour $tour */ @endphp
<div id="transports" role="tabpanel" class="tab-pane fade">
    <div id="transports-details">
        <table id="transports-table" class="datatable table table-striped table-responsive-sm">
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Name</th>
                    <th scope="col">Transport Number</th>
                    <th scope="col">Travel Class</th>
                    <th scope="col">Component Type</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Ordered</th>
                    <th scope="col">Bookable?</th>
                    <th scope="col">Stock Controlled?</th>
                    <th scope="col">Notes</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            @foreach($tour->transportInventoryTours as $tourComponent)
                <tr>
                    <td style="min-width: 200px" data-sort="{{$tourComponent->inventory->departs_at?->unix()}}">
                        {{ f_datetime($tourComponent->inventory->departs_at) }}
                        to {{ f_datetime($tourComponent->inventory->arrives_at) }}</td>
                    <td>{{ $tourComponent->inventory->component->name }}</td>
                    <td>{{ $tourComponent->inventory->transport_number }}</td>
                    <td>{{ $tourComponent->inventory->travelClass->name }}</td>
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
                    <td>{{ $tourComponent->inventory->component->internal_notes }}</td>
                    <td class="actions-3">
                        @can('update', \App\Models\Transport\TransportInventoryTour::class)
                            @if($tourComponent->tour_component_type !== 'Add-on')
                                <a href="{{ route('transport-upgrade.view', ['tour' => $tour, 'inventoryTour' => $tourComponent->tour_component_type == 'Upgrade' ? $tourComponent->parent() : $tourComponent,]) }}"
                                   class="btn btn-outline-success btn-sm mb-1" title="Upgrade">{{ Icon::upgrade() }}</a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::upgrade() }}
                                </span>
                            @endif
                            <a href="{{ route('transport-inventory-tours.edit', ['tour' => $tour, 'inventoryTour' => $tourComponent,]) }}"
                               class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::upgrade() }}
                            </span>
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::edit() }}
                            </span>
                        @endcan

                        @if(Bouncer::can('delete', \App\Models\Transport\TransportInventoryTour::class) && $tourComponent->repository->canDelete())
                            <a href="#" title="Delete" onclick="$('#transport-{{$tourComponent->id}}-delete').submit()"
                               class="btn btn-outline-danger btn-sm mb-1">
                                {{ Icon::delete() }}
                            </a>
                            <form action="{{ route('transport-inventory-tours.delete', ['tour' => $tour, 'inventoryTour' => $tourComponent,]) }}" method="post" id="transport-{{$tourComponent->id}}-delete">
                                @csrf
                            </form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1" title="{{ Bouncer::can('delete', \App\Models\Transport\TransportInventoryTour::class) ? 'Has Dependants' : 'Insufficient Permissions' }}">
                                {{ Icon::delete() }}
                        </span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
