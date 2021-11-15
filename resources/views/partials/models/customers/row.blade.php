<tr>
    <td><a href="{{ route('customers.view', ['customer' => $customer,]) }}">{{ $name }}</a></td>
    <td>{{ $date_of_birth }}</td>
    <td>{{ $home_address }}</td>
    <td>{{ $mobile_number }}</td>
    <td>{{ $passport_expiry_date }}</td>
    <td class="actions">
        <a href="{{route('customers.edit', ['customer' => $customer,])}}" class="btn btn-outline-success btn-sm mb-1">
            <i class="icon-note"></i>
        </a>
        <a href="#" class="btn btn-outline-danger btn-sm mb-1"
           onclick="event.preventDefault();document.getElementById('customer-{{ $customer->id }}-delete').submit();">
            <i class="icon-trash"></i>
        </a>
        <form id="customer-{{ $customer->id }}-delete"
              action="{{ route('customers.delete', ['customer' => $customer,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
