@extends('layout.main')

@section('title', 'All Room Types')

@section('content')
    <a class="btn btn-primary" href="{{ route('room-types.create') }}">Create New</a>
    <table id="roomType" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Room Type Name</th>
            <th scope="col">Maximum Occupancy</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($roomTypes as $roomType)
            @include('partials.models.room_types.row', [
              'roomType' => $roomType,
              'name' => $roomType->name,
              'maximum_occupancy' => $roomType->maximum_occupancy,
            ])
        @endforeach
    </table>
@endsection
