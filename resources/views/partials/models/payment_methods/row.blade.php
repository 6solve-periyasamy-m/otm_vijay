<tr>
    <td><a href="{{ route('payment-methods.view', ['paymentMethod' => $paymentMethod,]) }}">{{ $name }}</a></td>
    <td>
        <a href="{{route('payment-methods.edit', ['paymentMethod' => $paymentMethod,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('paymentMethod-{{ $paymentMethod->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="paymentMethod-{{ $paymentMethod->id }}-delete"
              action="{{ route('payment-methods.delete', ['paymentMethod' => $paymentMethod,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
