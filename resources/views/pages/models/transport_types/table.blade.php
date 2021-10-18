@extends('layout.main')

@section('title', 'All Transport Types')

@section('content')
    <a class="btn btn-primary" href="{{ route('transport-types.create') }}">Create New</a>
    <table id="transportType" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($transportTypes as $transportType)
            @include('partials.models.transport_types.row', [
              'transportType' => $transportType,
              'name' => $transportType->name,
            ])
        @endforeach
    </table>
@endsection
