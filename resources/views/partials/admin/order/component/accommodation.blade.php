
@php /** @var \App\Models\Order\Order $order; */ @endphp
<div id="accommodation-details">
    <table id="accommodation-table" class="datatable table table-striped table-responsive-sm">
        <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Customer</th>
            <th scope="col">Name</th>
            <th scope="col">Room Type</th>
            <th scope="col">Board Type</th>
            <th scope="col">Shared With</th>
            <th scope="col">Component Type</th>
            <th scope="col">Cost</th>
            <th scope="col">Purchase Price</th>
            <th scope="col">Updated Date</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($order->orderCustomers as $orderCustomer)
            @foreach($orderCustomer->orderAccommodation as $orderAccommodation)
                <tr component="{{ $orderAccommodation->id }}">
                    <td style="min-width: 150px">{{ f_datetime($orderAccommodation->tourComponent->inventory->check_in) }} to {{ f_datetime($orderAccommodation->tourComponent->inventory->check_out) }}</td>
                    <td>
                        @can('read', \App\Models\Order\OrderCustomer::class)
                            <a href="{{ route('order-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer]) }}" class="link-info" title="View customer details">
                                {{ $orderCustomer->customer->first_name . ' ' . $orderCustomer->customer->last_name }}
                            </a>
                        @else
                            {{ $orderCustomer->customer->first_name . ' ' . $orderCustomer->customer->last_name }}
                        @endcan
                    </td>
                    <td>{{ $orderAccommodation->tourComponent->inventory->accommodation->name }}</td>
                    <td>{{ $orderAccommodation->tourComponent->inventory->roomType->name }}</td>
                    <td>{{ $orderAccommodation->tourComponent->inventory->boardType->name }}</td>
                    <td>{{ empty($orderAccommodation->group->getMembers($orderCustomer)) ? 'Not Shared' : $orderAccommodation->group->getMembers($orderCustomer) }}</td>
                    <td>{{ $orderAccommodation->tourComponent->tour_component_type }}</td>
                    <td>
                        @if($orderAccommodation->tourComponent->tour_component_type == 'Included')
                            {{ f_currency(0) }}
                        @else
                            {{ f_currency($orderAccommodation->cost) }}
                        @endif
                    </td>
                    <td>
                        {{ fr_currency($orderAccommodation->tourComponent->inventory->purchase_price, $orderAccommodation->tourComponent->inventory->repository->getCurrency()) }}
                        ({{ fr_currency($orderAccommodation->purchase_price) }} @includeWhen($orderAccommodation->estimated_purchase_price === null, 'partials.admin.order.component.epp-calculated', []))
                    </td>
                    <td>{{ f_date($orderAccommodation->updated_at) }}</td>
                    <td>
                        @can('update', \App\Models\Order\Component\OrderAccommodation::class)
                        <button onclick="openModal('admin.order.component.order-accommodation-form', {'component': {{$orderAccommodation->id}}})" class="btn btn-sm btn-outline-warning" style="height: 33px;" title="Edit accommodation">
                            {{ Icon::edit() }}
                        </button>
                        @endcan
                        <form action="{{ route('orderAccommodationDelete', ['id' => $orderAccommodation->id,]) }}" method="post">
                            @csrf
                            <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['order' => $order,]) }}"/>
                                <a href="#" onclick="this.parentNode.submit()" class="btn btn-outline-danger btn-sm"  title="Delete accommodation">{{ Icon::delete() }}</a>
                        </form>
                    </td>
                </tr>
            @endforeach
        @endforeach
    </table>
</div>