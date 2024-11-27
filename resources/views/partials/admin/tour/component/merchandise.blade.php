@php /** @var \App\Models\Tour\Tour $tour */ @endphp
<div id="merchandise" role="tabpanel" class="tab-pane fade">
    <div id="merchandise-details">
        <table id="merchandise-table" class="datatable table table-striped table-responsive-sm">
            <thead>
                <tr>
                    <th scope="col">Icon</th>
                    <th scope="col">Name</th>
                    <th scope="col">Variant</th>
                    <th scope="col">Size</th>
                    <th scope="col">Component Type</th>
                    <th scope="col">Stock Controlled?</th>
                    <th scope="col">Sales Price</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Ordered</th>
                    <th scope="col">Notes</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            @foreach($tour->merchandise as $tourComponent)
                <tr>
                    <td><img src="{{ $tourComponent->inventory->asset }}" class="image tiny"/></td>
                    <td style="min-width: 100px">{{ $tourComponent->inventory->component->name }}</td>
                    <td>{{ $tourComponent->inventory->variant->name }}</td>
                    <td>{{ $tourComponent->inventory->size?->name ?? 'No Size'  }}</td>
                    <td>{{ $tourComponent->tour_component_type }}</td>
                    <td>{{ f_bool($tourComponent->stock_control_active) }}</td>
                    <td>{{ f_currency($tourComponent->tour_sales_price) }}</td>
                    <td>
                        {{ $tourComponent->repository->getUsedStock() }}
                        /{{ $tourComponent->repository->getTotalStock() }}<br/>
                        ({{$tourComponent->repository->getAvailableStock()}} Available)
                    </td>
                    <td>{{ $tourComponent->repository->getOrderedCount() }} Ordered, {{ $tourComponent->repository->getBookedCount() }} <abbr title="Bookings created through the form. Will include ones converted to orders">Booked</abbr></td>
                    <td>{{ $tourComponent->repository->getInventoryInternalNotes() }}</td>
                    <td class="actions">
                        @can('update', \App\Models\Merchandise\MerchandiseInventoryTour::class)
                            <a href="{{ route('merchandise.inventory.tour.edit', ['tour' => $tour, 'inventoryTour' => $tourComponent,]) }}"
                               class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1" title="Delete">
                                {{ Icon::edit() }}
                            </span>
                        @endcan
                        @if(Bouncer::can('delete', \App\Models\Merchandise\MerchandiseInventoryTour::class) && $tourComponent->repository->canDelete())
                            <a href="#" title="Delete" onclick="$('#merchandise-{{$tourComponent->id}}-delete').submit()"
                               class="btn btn-outline-danger btn-sm mb-1">
                                {{ Icon::delete() }}
                            </a>
                            <form action="{{ route('merchandise.inventory.tour.delete', ['tour' => $tour, 'inventoryTour' => $tourComponent,]) }}" method="post" id="merchandise-{{$tourComponent->id}}-delete">
                                @csrf
                            </form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1" title="{{ Bouncer::can('delete', \App\Models\Merchandise\MerchandiseInventoryTour::class) ? 'Has Dependants' : 'Insufficient Permissions' }}">
                                    {{ Icon::delete() }}
                            </span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
