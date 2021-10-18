@extends('layout.main')

@section('title', 'All Location Types')

@section('content')
    <a class="btn btn-primary" href="{{ route('location-types.create') }}">Create New</a>
    <table id="locationType" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($locationTypes as $locationType)
            @include('partials.models.location_types.row', [
              'locationType' => $locationType,
              'name' => $locationType->name,
            ])
        @endforeach
    </table>
@endsection
