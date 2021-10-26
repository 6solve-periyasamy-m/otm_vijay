<tr>
    <td class="date">{{ $quantity }}</td>
    <td class="description">{!! nl2br($description) !!} </td>
    <td class="@if($negative) amount-negative @else amount-positive @endif">{{ $cost }}</td>
</tr>
