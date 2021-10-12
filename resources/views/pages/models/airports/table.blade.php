@extends('layout.main')

@section('title', 'Update Airports')

@section('content')
    <a class="btn btn-primary" href="{{ route('airports.create') }}">Create New</a>
    <table id="airport" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Iata Code</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($airports as $airport)
            @include('partials.models.airports.row', [
              'airport' => $airport,
              'name' => $airport->name,
              'iata_code' => $airport->iata_code,
            ])
        @endforeach
    </table>
@endsection
