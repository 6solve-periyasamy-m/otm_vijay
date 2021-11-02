<tr>
    <td>
        <a href="{{ route('activity-inventories.view', ['activityInventory' => $activityInventory,]) }}">{{ $activity_id }}</a>
    </td>
    <td>{{ $ticket_type_id }}</td>
    <td>{{ $starts_at }}</td>
    <td>{{ $ends_at }}</td>
    <td>{{ $fit_selectable }}</td>
    <td>{{ $stock }}</td>
    <td>{{ $purchase_price }}</td>
    <td>{{ $sales_price }}</td>
    <td>{{ $currency }}</td>
    <td>{{ $notes }}</td>
    <td>
        <a href="{{route('activity-inventories.edit', ['activityInventory' => $activityInventory,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('activityInventory-{{ $activityInventory->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="activityInventory-{{ $activityInventory->id }}-delete"
              action="{{ route('activity-inventories.delete', ['activityInventory' => $activityInventory,]) }}"
              method="POST" style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
