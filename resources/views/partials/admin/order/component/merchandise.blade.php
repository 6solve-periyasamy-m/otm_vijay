
@php /** @var \App\Models\Order\Order $order; */ @endphp
<div id="merchandise-details">
    <table id="merchandise-table" class="datatable table table-striped table-responsive-sm">
        <thead>
        <tr>
            <th scope="col">Image</th>
            <th scope="col">Customer</th>
            <th scope="col">Name</th>
            <th scope="col">Cost</th>
            <th scope="col">Component Type</th>
            <th scope="col">Fulfilled</th>
            <th scope="col" class="actions">Actions</th>
        </tr>
        </thead>
        @foreach($order->orderCustomers as $orderCustomer)
            @foreach($orderCustomer->orderMerchandise()->with('tourComponent', 'tourComponent.inventory', 'tourComponent.inventory.component')->get() as $orderMerchandise)
                <tr>
                    <td><img src="{{ $orderMerchandise->tourComponent->inventory->asset }}" class="image tiny"/></td>
                    <td>
                        @can('read', \App\Models\Order\OrderCustomer::class)
                            <a href="{{ route('order-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer]) }}" class="link-info" title="View customer details">
                                {{ $orderCustomer->customer->first_name . ' ' . $orderCustomer->customer->last_name }}
                            </a>
                        @else
                            {{ $orderCustomer->customer->first_name . ' ' . $orderCustomer->customer->last_name }}
                        @endcan
                    </td>
                    <td>{{ $orderMerchandise->tourComponent->inventory->component->name }}
                        ({{ $orderMerchandise->tourComponent->inventory->variant->name }})
                        ({{ $orderMerchandise->tourComponent->inventory->size?->name ?? 'No Size'  }})
                    </td>
                    <td>{{ f_currency($orderMerchandise->tourComponent->tour_sales_price) }}</td>
                    <td>{{ $orderMerchandise->tourComponent->tour_component_type }}</td>
                    <td>{{ f_bool($orderMerchandise->fulfilled) }}</td>
                    <td class="actions">                        
                        <a href="javascript:$('#m-{{$orderMerchandise->id}}-delete').submit()" class="btn btn-outline-danger btn-sm" title="Delete merchandise">{{ Icon::delete() }}</a>
                        <form action="{{ route('orderMerchandiseDelete', ['id' => $orderMerchandise->id,]) }}"  method="post" id="m-{{$orderMerchandise->id}}-delete" class="d-none">
                            @csrf
                            <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['order' => $order,]) }}"/>
                        </form>
                    </td>
                </tr>
            @endforeach
        @endforeach
    </table>
</div>