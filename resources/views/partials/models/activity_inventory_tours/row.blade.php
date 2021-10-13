<tr>
    <td>
        <a href="{{ route('activity-inventory-tours.view', ['activityInventoryTour' => $activityInventoryTour,]) }}">{{ $tour_id }}</a>
    </td>
    <td>{{ $activity_inventory_id }}</td>
    <td>{{ $tour_component_type }}</td>
    <td>{{ $tour_sales_price }}</td>
    <td>
        <a href="{{route('activity-inventory-tours.edit', ['activityInventoryTour' => $activityInventoryTour,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('activityInventoryTour-{{ $activityInventoryTour->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="activityInventoryTour-{{ $activityInventoryTour->id }}-delete"
              action="{{ route('activity-inventory-tours.delete', ['activityInventoryTour' => $activityInventoryTour,]) }}"
              method="POST" style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
