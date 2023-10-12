<tr>
    <td>{{ $address->name ?? 'Not Set' }}</td>
    <td>{{ $address->locationType ?? 'Not Set' }}</td>
    <td>{{ ucwords($address->parent) ?? 'Not Set' }}</td>
    <td>{{ $address->address_line_1 ?? 'Not Set' }}</td>
    <td>{{ $address->address_line_2 ?? '' }}</td>
    <td>{{ $address->town ?? '' }}</td>
    <td>{{ $address->region ?? '' }}</td>
    <td>{{ $address->country ?? 'Not Set' }}</td>
    <td>{{ $address->postcode ?? 'Not Set' }}</td>
    <td class="actions">
        <a href="{{route('addresses.edit', ['address' => $address,])}}" class="btn btn-outline-success btn-sm mb-1">
            {{ Icon::edit() }}
        </a>
    </td>
</tr>
