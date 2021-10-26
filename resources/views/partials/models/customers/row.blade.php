<tr>
    <td><a href="{{ route('customers.view', ['customer' => $customer,]) }}">{{ $name }}</a></td>
    <td>{{ $date_of_birth }}</td>
    <td>{{ $home_address }}</td>
    <td>{{ $mobile_number }}</td>
    <td>{{ $passport_expiry_date }}</td>
    <td>
        <a href="{{route('customers.edit', ['customer' => $customer,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('customer-{{ $customer->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="customer-{{ $customer->id }}-delete"
              action="{{ route('customers.delete', ['customer' => $customer,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
