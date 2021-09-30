<tr>
  <td><a href="{{ route('orderCustomerAdjustments.view', ['orderCustomerAdjustment' => $orderCustomerAdjustment,]) }}">{{ $order_customer_id }}</a></td>
  <td>{{ $amount }}</td>
  <td>{{ $reason }}</td>
  <td>
    <a href="{{route('order_customer_adjustments.edit', ['orderCustomerAdjustment' => $orderCustomerAdjustment,])}}"><ion-icon name="create"></ion-icon></a>
    <a href="#" onclick="event.preventDefault();document.getElementById('orderCustomerAdjustment-{{ $orderCustomerAdjustment->id }}-delete').submit();"><ion-icon name="trash"></ion-icon></a>
    <form id="orderCustomerAdjustment-{{ $orderCustomerAdjustment->id }}-delete" action="{{ route('order_customer_adjustments.delete', ['orderCustomerAdjustment' => $orderCustomerAdjustment,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
  </td>
</tr>
