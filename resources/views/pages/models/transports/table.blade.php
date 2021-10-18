@extends('layout.main')

@section('title', 'All Transports')

@section('content')
    <a class="btn btn-primary" href="{{ route('transports.create') }}">Create New</a>
    <table id="transport" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Transport Type</th>
            <th scope="col">Operator</th>
            <th scope="col">Departure Location</th>
            <th scope="col">Arrival Location</th>
            <th scope="col">Description</th>
            <th scope="col">Currency</th>
            <th scope="col">Is Domestic</th>
            <th scope="col">Notes</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($transports as $transport)
            @include('partials.models.transports.row', [
              'transport' => $transport,
              'name' => $transport->name,
              'description' => $transport->description,
              'currency' => $transport->currency,
              'is_domestic' => $transport->is_domestic,
              'notes' => $transport->notes,
            ])
        @endforeach
    </table>
@endsection
