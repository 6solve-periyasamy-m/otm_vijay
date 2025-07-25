@php /** @var \App\Models\Order\Order $order; */ @endphp

<div id="flights-details">
    <table id="flights-table" class="datatable table table-striped table-responsive-sm">
        <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Customer</th>
            <th scope="col">Name</th>
            <th scope="col">Flight Details</th>
            <th scope="col">Travel Class</th>
            <th scope="col">Component Type</th>
            <th scope="col">Cost</th>
            <th scope="col">Purchase Price</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($order->orderCustomers as $orderCustomer)
            @foreach($orderCustomer->orderFlights as $orderFlight)
                <tr component="{{ $orderFlight->id }}">
                    <td style="min-width: 200px">{{ f_datetime($orderFlight->flight_inventory->departs_at) }} to {{ f_datetime($orderFlight->flight_inventory->arrives_at) }}</td>
                    <td>
                        @can('read', \App\Models\Order\OrderCustomer::class)
                            <a href="{{ route('order-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer]) }}" class="link-info" title="View customer details">
                                {{ $orderCustomer->customer->first_name . ' ' . $orderCustomer->customer->last_name }}
                            </a>
                        @else
                            {{ $orderCustomer->customer->first_name . ' ' . $orderCustomer->customer->last_name }}
                        @endcan
                    </td>
                    <td>{{ $orderFlight->flight_number }}</td>
                    <td>{{ $orderFlight->flight->departureAirport->name }} to {{ $orderFlight->flight->arrivalAirport->name }}</td>
                    <td>{{ $orderFlight->flight_inventory->travelClass->name }}</td>
                    <td>{{ $orderFlight->flightInventoryTour->tour_component_type }}</td>
                    <td>
                        @if($orderFlight->tourComponent->tour_component_type == 'Included')
                            {{ f_currency(0) }}
                        @else
                            {{ f_currency($orderFlight->cost) }}
                        @endif
                    </td>
                    <td>{{ f_currency($orderFlight->purchase_price) }} @includeWhen($orderFlight->estimated_purchase_price === null, 'partials.admin.order.component.epp-calculated', [])</td>
                    <td class="actions">
                        @can('update', \App\Models\Order\Component\OrderFlight::class)
                        <button onclick="openModal('admin.order.component.order-flight-form', {'component': {{$orderFlight->id}}})" class="btn btn-sm btn-outline-warning" style="height: 33px;" title="Edit flight">
                            {{ Icon::edit() }}
                        </button>
                        @endcan
                        <form style="display:inline-block;" action="{{ route('orderFlightDelete', ['id' => $orderFlight->id,]) }}"  method="post">
                            @csrf
                            <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['order' => $order,]) }}"/>
                            <a href="#" onclick="this.parentNode.submit()" class="btn btn-outline-danger btn-sm"  title="Delete flight">{{ Icon::delete() }}</a>
                        </form>
                    </td>
                </tr>
            @endforeach
        @endforeach
    </table>
</div>
