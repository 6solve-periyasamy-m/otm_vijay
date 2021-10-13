<tr>
    <td><a href="{{ route('manual-adjustments.view', ['manualAdjustment' => $manualAdjustment,]) }}">{{ $order_id }}</a>
    </td>
    <td>{{ $amount }}</td>
    <td>{{ $reason }}</td>
    <td>
        <a href="{{route('manual-adjustments.edit', ['manualAdjustment' => $manualAdjustment,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('manualAdjustment-{{ $manualAdjustment->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="manualAdjustment-{{ $manualAdjustment->id }}-delete"
              action="{{ route('manual-adjustments.delete', ['manualAdjustment' => $manualAdjustment,]) }}"
              method="POST" style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
