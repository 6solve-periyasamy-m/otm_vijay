@php /** @var \App\Models\Order\Order $order; */ @endphp
<div id="activities-details">
    <table id="activities-table" class="datatable table table-striped table-responsive-sm">
        <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Customer</th>
            <th scope="col">Name</th>
            <th scope="col">Activity Type</th>
            <th scope="col">Ticket Type</th>
            <th scope="col">Component Type</th>
            <th scope="col">Cost</th>
            <th scope="col">Purchase Price</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($order->orderCustomers as $orderCustomer)
            @foreach($orderCustomer->orderActivities as $orderActivity)
                <tr component="{{ $orderActivity->id }}">
                    <td style="min-width: 200px">{{ f_datetime($orderActivity->activity_inventory->starts_at) }} to {{ f_datetime($orderActivity->activity_inventory->ends_at) }}</td>                    
                    <td>
                        @can('read', \App\Models\Order\OrderCustomer::class)
                            <a href="{{ route('order-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer]) }}" class="link-info" title="View customer details">
                                {{ $orderCustomer->customer->first_name . ' ' . $orderCustomer->customer->last_name }}
                            </a>
                        @else
                            {{ $orderCustomer->customer->first_name . ' ' . $orderCustomer->customer->last_name }}
                        @endcan
                    </td>
                    <td>{{ $orderActivity->activity->name }}</td>
                    <td>{{ $orderActivity->activity->activityType->name }}</td>
                    <td>{{ $orderActivity->activity_inventory->ticketType->name }}</td>
                    <td>{{ $orderActivity->tourComponent->tour_component_type }}</td>
                    <td>
                        @if($orderActivity->tourComponent->tour_component_type == 'Included')
                            {{ f_currency(0) }}
                        @else
                            {{ f_currency($orderActivity->cost) }}
                        @endif
                    </td>
                    <td>{{ f_currency($orderActivity->purchase_price) }} @includeWhen($orderActivity->estimated_purchase_price === null, 'partials.admin.order.component.epp-calculated', [])</td>
                    <td>
                        <div class="d-flex gap-1">
                            @can('update', \App\Models\Order\Component\OrderActivity::class)
                            <button onclick="openModal('admin.order.component.order-activity-form', {'component': {{$orderActivity->id}}})" class="btn btn-sm btn-outline-warning" style="height: 33px;" title="Edit activity">
                                {{ Icon::edit() }}
                            </button>
                            @endif
                            <form action="{{ route('orderActivityDelete', ['id' => $orderActivity->id,]) }}" method="post">
                                @csrf
                                <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['order' => $order,]) }}"/>
                                <a href="#" onclick="this.parentNode.submit()" class="btn btn-outline-danger btn-sm"  title="Delete activity">{{ Icon::delete() }}</a>
                            </form>    
                        </div>                      
                    </td>
                </tr>
            @endforeach
        @endforeach
    </table>
</div>
