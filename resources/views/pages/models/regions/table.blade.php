@extends('layout.main')

@section('title', 'All Regions')

@section('content')
    <a class="btn btn-primary" href="{{ route('regions.create') }}">Create New</a>
    <table id="region" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Country Id</th>
            <th scope="col">Name</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($regions as $region)
            @include('partials.models.regions.row', [
              'region' => $region,
              'country_id' => $region->country_id,
              'name' => $region->name,
            ])
        @endforeach
    </table>
@endsection
