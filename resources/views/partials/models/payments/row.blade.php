<tr>
    <td><a href="{{ route('payments.view', ['payment' => $payment,]) }}">{{ $order_id }}</a></td>
    <td>{{ $payment_method_id }}</td>
    <td>{{ $amount }}</td>
    <td>{{ $reason }}</td>
    <td>
        <a href="{{route('payments.edit', ['payment' => $payment,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('payment-{{ $payment->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="payment-{{ $payment->id }}-delete" action="{{ route('payments.delete', ['payment' => $payment,]) }}"
              method="POST" style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
