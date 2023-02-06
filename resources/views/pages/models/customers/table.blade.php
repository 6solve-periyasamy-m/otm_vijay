@extends('layout.master')

@section('title', 'All Customers')

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#customer').DataTable({fixedHeader: true});
        });
    </script>
@endsection

@section('content')
    <div class="card">
        <div class="card-body text-end">
            <a class="btn btn-primary text-white" href="{{ route('customers.create') }}">
                <x-icon icon="plus" />
                Create New
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="customer" style="width: 100%;" class="table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email Address</th>
                    <th scope="col">Registered</th>
                    <th scope="col">Date of Birth</th>
                    <th scope="col">Home Address</th>
                    <th scope="col">Mobile Number</th>
                    <th scope="col">Passport Expiry Date</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                @foreach($customers as $customer)
                    <tr>
                        <td><a href="{{ route('customers.view', ['customer' => $customer,]) }}">{{ $customer->full_name }}</a></td>
                        <td>{{ $customer->email_address ?? 'No Email Address' }}</td>
                        <td>{{ f_bool($customer->registered) }}</td>
                        <td>{{ isset($customer->date_of_birth) ? f_date($customer->date_of_birth) : 'Date of Birth not set' }}</td>
                        <td>{{ $customer->homeAddress }}</td>
                        <td>{{ $customer->mobile_number }}</td>
                        <td>{{ f_date($customer->passport_expiry_date) }}</td>
                        <td class="actions">
                            <a href="{{route('customers.edit', ['customer' => $customer,])}}" class="btn btn-outline-success btn-sm mb-1">
                                <x-icon icon="note" />
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-sm mb-1"
                               onclick="event.preventDefault();document.getElementById('customer-{{ $customer->id }}-delete').submit();">
                                <x-icon icon="trash" />
                            </a>
                            <form id="customer-{{ $customer->id }}-delete"
                                  action="{{ route('customers.delete', ['customer' => $customer,]) }}" method="POST"
                                  style="display: none;">{{ csrf_field() }}</form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
