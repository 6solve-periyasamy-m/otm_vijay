@php /** @var \App\Models\Order\Order $order; */ @endphp
<div id="transports-details">
    <table id="transports-table" class="datatable table table-striped table-responsive-sm">
        <thead>
        <tr>
            <th scope="col">Date</th>            
            <th scope="col">Customer</th>
            <th scope="col">Name</th>
            <th scope="col">Transport Type</th>
            <th scope="col">Transport Information</th>
            <th scope="col">Travel Class</th>
            <th scope="col">Component Type</th>
            <th scope="col">Cost</th>
            <th scope="col">Purchase Price</th>
            <th scope="col">Updated Date</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($order->orderCustomers as $orderCustomer)
            @foreach($orderCustomer->orderTransports as $orderTransport)
                @php $inventory = $orderTransport->transport_inventory; @endphp                
                <tr component="{{ $orderTransport->id }}">
                    <td style="min-width: 200px">{{ f_datetime($orderTransport->repository->getStartTime()) }} to {{ f_datetime($orderTransport->repository->getEndTime()) }}</td>                    
                    <td>
                        @can('read', \App\Models\Order\OrderCustomer::class)
                            <a href="{{ route('order-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer]) }}" class="link-info" title="View customer details">
                                {{ $orderCustomer->customer->first_name . ' ' . $orderCustomer->customer->last_name }}
                            </a>
                        @else
                            {{ $orderCustomer->customer->first_name . ' ' . $orderCustomer->customer->last_name }}
                        @endcan
                    </td>
                    <td>{{ $orderTransport->transport->name }}</td>
                    <td>{{ $orderTransport->transport->transportType->name }}</td>
                    <td>{{ $orderTransport->transport->departureAddress->name }} to {{ $orderTransport->transport->arrivalAddress->name }}</td>
                    <td>{{ $orderTransport->transport_inventory->travelClass->name }}</td>
                    <td>{{ $orderTransport->transportInventoryTour->tour_component_type }}</td>
                    <td>
                        @if($orderTransport->tourComponent->tour_component_type == 'Included')
                            {{ f_currency(0) }}
                        @else
                            {{ f_currency($orderTransport->cost) }}
                        @endif
                    </td>
                    <td>
                        {{ fr_currency($orderTransport->tourComponent->inventory->purchase_price, $orderTransport->tourComponent->inventory->repository->getCurrency()) }}
                        ({{ fr_currency($orderTransport->purchase_price) }} @includeWhen($orderTransport->estimated_purchase_price === null, 'partials.admin.order.component.epp-calculated', []))
                    </td>
                    <td>{{ f_date($orderTransport->updated_at) }}</td>
                    <td class="actions">
                        @can('update', \App\Models\Order\Component\OrderTransport::class)
                        <button onclick="openModal('admin.order.component.order-transport-form', {'component': {{$orderTransport->id}}})" class="btn btn-sm btn-outline-warning" style="height: 33px;" title="Edit transport">
                            {{ Icon::edit() }}
                        </button>
                        @endcan
                        <form style="display:inline-block;" action="{{ route('orderTransportDelete', ['id' => $orderTransport->id,]) }}" method="post">
                            @csrf
                            <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['order' => $order,]) }}"/>
                            <a href="#" onclick="this.parentNode.submit()" class="btn btn-outline-danger btn-sm"  title="Delete transport">{{ Icon::delete() }}</a>
                        </form>
                    </td>
                </tr>
            @endforeach
        @endforeach
    </table>
</div>