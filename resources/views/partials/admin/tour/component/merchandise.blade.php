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
            @foreach($tour->merchandise as $merchandise)
                <tr>
                    <td><img src="{{ $merchandise->inventory->asset }}" class="image tiny"/></td>
                    <td style="min-width: 100px">{{ $merchandise->inventory->component->name }}</td>
                    <td>{{ $merchandise->inventory->variant->name }}</td>
                    <td>{{ $merchandise->inventory->size?->name ?? 'No Size'  }}</td>
                    <td>{{ $merchandise->stock_control_active }}</td>
                    <td>{{ f_currency($merchandise->tour_sales_price) }}</td>
                    <td>
                        {{ $merchandise->repository->getUsedStock() }}
                        /{{ $merchandise->repository->getTotalStock() }}<br/>
                        ({{$merchandise->repository->getAvailableStock()}} Available)
                    </td>
                    <td>{{ $merchandise->repository->getOrderedCount() }} Ordered, {{ $merchandise->repository->getBookedCount() }} <abbr title="Bookings created through the form. Will include ones converted to orders">Booked</abbr></td>
                    <td>{{ $merchandise->internal_notes }}</td>
                    <td class="actions">
                        @can('update', Merchandise::class)
                            <a href="{{ route('merchandise.inventory.tour.edit', ['tour' => $tour, 'inventoryTour' => $merchandise,]) }}"
                               class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1" title="Delete">
                                            {{ Icon::delete() }}
                                        </span>
                        @endcan
                        @can('delete', Merchandise::class)
                            <a href="#" title="Delete" onclick="$('#merchandise-{{$merchandise->id}}-delete').submit()"
                               class="btn btn-outline-{{ $merchandise->is_bookable ? 'danger' : 'warning' }} btn-sm mb-1">
                                {{ $merchandise->is_bookable ? Icon::delete() : Icon::enable() }}
                            </a>
                            <form action="{{ route($merchandise->is_bookable ? 'merchandise.inventory.tour.delete' : 'merchandise.inventory.tour.restore',['tour' => $tour, 'inventoryTour' => $merchandise,]) }}"
                                  method="post" id="merchandise-{{$merchandise->id}}-delete">
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
