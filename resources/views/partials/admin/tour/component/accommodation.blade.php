@php /** @var \App\Models\Tour\Tour $tour */ @endphp
<div id="accommodation" role="tabpanel" class="tab-pane fade">
    <div id="accommodation-details">
        <table id="accommodation-table" class="datatable table table-striped table-responsive-sm">
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Name</th>
                    <th scope="col">Room Type</th>
                    <th scope="col">Board Type</th>
                    <th scope="col">Template?</th>
                    <th scope="col">Component Type</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Ordered</th>
                    <th scope="col">Bookable?</th>
                    <th scope="col">Stock Controlled?</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            @foreach($tour->accommodationInventoryTours as $tourComponent)
                <tr>
                    <td style="min-width: 200px" data-sort="{{$tourComponent->inventory->check_in?->unix()}}">
                        {{ f_datetime($tourComponent->inventory->check_in) }}
                        <input type="checkbox" disabled
                               @if($tourComponent->inventory->check_in_time_confirmed == 1) checked @endif>
                        &nbsp;to&nbsp;
                        {{ f_datetime($tourComponent->inventory->check_out) }}
                        <input type="checkbox" disabled
                               @if($tourComponent->inventory->check_out_time_confirmed == 1) checked @endif>
                    </td>
                    <td>{{ $tourComponent->inventory->component->name }}</td>
                    <td>{{ $tourComponent->inventory->roomType->name }}</td>
                    <td>{{ $tourComponent->inventory->boardType->name }}</td>
                    <td>{{ f_bool($tourComponent->is_template) }}</td>
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
                        @can('update', \App\Models\Accommodation\AccommodationInventoryTour::class)
                            @if($tourComponent->tour_component_type !== 'Add-on')
                                <a href="{{ route('accommodation-upgrade.view', ['tour' => $tour, 'inventoryTour' => $tourComponent->tour_component_type == 'Upgrade' ? $tourComponent->parent() : $tourComponent,]) }}"
                                   class="btn btn-outline-success btn-sm mb-1" title="Upgrade">{{ Icon::upgrade() }}</a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::upgrade() }}
                                </span>
                            @endif
                            <a href="{{ route('accommodation-inventory-tours.edit', ['tour' => $tour, 'inventoryTour' => $tourComponent,]) }}"
                               class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::upgrade() }}
                            </span>
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::edit() }}
                            </span>
                        @endcan
                        @if(Bouncer::can('delete', \App\Models\Accommodation\AccommodationInventoryTour::class) && $tourComponent->repository->canDelete())
                            <a href="#" title="Delete" onclick="$('#accommodation-{{$tourComponent->id}}-delete').submit()"
                               class="btn btn-outline-danger btn-sm mb-1">
                                {{ Icon::delete() }}
                            </a>
                            <form action="{{ route('accommodation-inventory-tours.delete', ['tour' => $tour, 'inventoryTour' => $tourComponent,]) }}" method="post" id="accommodation-{{$tourComponent->id}}-delete">
                                @csrf
                            </form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1" title="{{ Bouncer::can('delete', \App\Models\Accommodation\AccommodationInventoryTour::class) ? 'Has Dependants' : 'Insufficient Permissions' }}">
                                {{ Icon::delete() }}
                            </span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
