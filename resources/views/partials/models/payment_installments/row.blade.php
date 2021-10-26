<tr>
  <td><a href="{{ route('payment-installments.view', ['paymentInstallment' => $paymentInstallment,]) }}">{{ $due_on }}</a></td>
  <td>{{ $amount }}</td>
  <td>
    <a href="{{route('payment-installments.edit', ['paymentInstallment' => $paymentInstallment,])}}"><ion-icon name="create"></ion-icon></a>
    <a href="#" onclick="event.preventDefault();document.getElementById('paymentInstallment-{{ $paymentInstallment->id }}-delete').submit();"><ion-icon name="trash"></ion-icon></a>
    <form id="paymentInstallment-{{ $paymentInstallment->id }}-delete" action="{{ route('payment-installments.delete', ['paymentInstallment' => $paymentInstallment,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
  </td>
</tr>
