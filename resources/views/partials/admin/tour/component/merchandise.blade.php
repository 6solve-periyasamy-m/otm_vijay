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
                    <td>{{ $tourComponent->stock_control_active }}</td>
                    <td>{{ f_currency($tourComponent->tour_sales_price) }}</td>
                    <td>
                        {{ $tourComponent->repository->getUsedStock() }}
                        /{{ $tourComponent->repository->getTotalStock() }}<br/>
                        ({{$tourComponent->repository->getAvailableStock()}} Available)
                    </td>
                    <td>{{ $tourComponent->repository->getOrderedCount() }} Ordered, {{ $tourComponent->repository->getBookedCount() }} <abbr title="Bookings created through the form. Will include ones converted to orders">Booked</abbr></td>
                    <td>{{ $tourComponent->internal_notes }}</td>
                    <td class="actions">
                        @can('update', Merchandise::class)
                            <a href="{{ route('merchandise.inventory.tour.edit', ['tour' => $tour, 'inventoryTour' => $tourComponent,]) }}"
                               class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1" title="Delete">
                                            {{ Icon::delete() }}
                                        </span>
                        @endcan
                        @can('delete', Merchandise::class)
                            <a href="#" title="Delete" onclick="$('#merchandise-{{$tourComponent->id}}-delete').submit()"
                               class="btn btn-outline-{{ $tourComponent->is_bookable ? 'danger' : 'warning' }} btn-sm mb-1">
                                {{ $tourComponent->is_bookable ? Icon::delete() : Icon::enable() }}
                            </a>
                            <form action="{{ route($tourComponent->is_bookable ? 'merchandise.inventory.tour.delete' : 'merchandise.inventory.tour.restore',['tour' => $tour, 'inventoryTour' => $tourComponent,]) }}"
                                  method="post" id="merchandise-{{$tourComponent->id}}-delete">
                                @csrf
                            </form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1" title="Delete">
                                    {{ Icon::delete() }}
                                </span>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
