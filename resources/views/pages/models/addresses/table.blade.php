@extends('layout.main')

@section('title', 'All Addresses')

@section('content')
    <a class="btn btn-primary" href="{{ route('addresses.create') }}">Create New</a>
    <table id="address" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Address Line 1</th>
            <th scope="col">Address Line 2</th>
            <th scope="col">Town</th>
            <th scope="col">Region</th>
            <th scope="col">Country</th>
            <th scope="col">Postcode</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($addresses as $address)
            @include('partials.models.addresses.row', [
              'address' => $address,
              'address_line_1' => $address->address_line_1,
              'address_line_2' => $address->address_line_2,
              'town' => $address->town,
              'region' => $address->region,
              'country' => $address->country,
              'postcode' => $address->postcode,
            ])
        @endforeach
    </table>
@endsection
