@extends('layout.master')

@section('title', 'All Customers')

@section('content')
    <x-admin.section.card>
        <div class="text-end">
            <a class="btn btn-primary text-white" href="{{ route('customers.create') }}">
                {{ Icon::create() }}
                Create New
            </a>
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <table id="customer" style="width: 100%;" class="datatable table table-striped">
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
                            {{ Icon::edit() }}
                        </a>
                        <a href="#" class="btn btn-outline-danger btn-sm mb-1"
                           onclick="event.preventDefault();document.getElementById('customer-{{ $customer->id }}-delete').submit();">
                            {{ Icon::delete() }}
                        </a>
                        <form id="customer-{{ $customer->id }}-delete"
                              action="{{ route('customers.delete', ['customer' => $customer,]) }}" method="POST"
                              style="display: none;">{{ csrf_field() }}</form>
                    </td>
                </tr>
            @endforeach
        </table>
    </x-admin.section.card>
@endsection
