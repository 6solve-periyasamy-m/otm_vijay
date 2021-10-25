@extends('layout.main')

@section('title', 'All Airlines')

@section('content')
    <a class="btn btn-primary" href="{{ route('airlines.create') }}">Create New</a>
    <table id="airline" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($airlines as $airline)
            @include('partials.models.airlines.row', [
              'airline' => $airline,
              'name' => $airline->name,
            ])
        @endforeach
    </table>
@endsection
