<tr>
    <td><a href="{{ route('customers.view', ['customer' => $customer,]) }}">{{ $title }}</a></td>
    <td>{{ $first_name }}</td>
    <td>{{ $middle_names }}</td>
    <td>{{ $last_name }}</td>
    <td>{{ $date_of_birth }}</td>
    <td>{{ $mobile_number }}</td>
    <td>{{ $other_phone_number }}</td>
    <td>{{ $email_address }}</td>
    <td>{{ $password }}</td>
    <td>{{ $gender }}</td>
    <td>{{ $emergency_contact_name }}</td>
    <td>{{ $emergency_contact_relationship }}</td>
    <td>{{ $emergency_contact_telephone }}</td>
    <td>{{ $passport_first_name }}</td>
    <td>{{ $passport_middle_name }}</td>
    <td>{{ $passport_last_name }}</td>
    <td>{{ $passport_number }}</td>
    <td>{{ $passport_issue_date }}</td>
    <td>{{ $passport_expiry_date }}</td>
    <td>{{ $t_shirt_size_id }}</td>
    <td>{{ $hat_size_id }}</td>
    <td>{{ $notes }}</td>
    <td>{{ $loyalty_number }}</td>
    <td>{{ $login_token }}</td>
    <td>{{ $home_address_id }}</td>
    <td>{{ $billing_address_id }}</td>
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
