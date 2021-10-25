@extends('layout.main')

@section('title', 'All Locations')

@section('content')
    <a class="btn btn-primary" href="{{ route('locations.create') }}">Create New</a>
    <table id="location" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Region Id</th>
            <th scope="col">Location Type Id</th>
            <th scope="col">Name</th>
            <th scope="col">Address</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($locations as $location)
            @include('partials.models.locations.row', [
              'location' => $location,
              'region_id' => $location->region_id,
              'location_type_id' => $location->location_type_id,
              'name' => $location->name,
              'address' => $location->address,
            ])
        @endforeach
    </table>
@endsection
