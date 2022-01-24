@extends('layout.master')

@section('title', 'All Merchandises')

@section('content')
    <a class="btn btn-primary" href="{{ route('merchandise.create') }}">Create New</a>
    <table id="merchandise" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($merchandises as $merchandise)
            @include('partials.models.merchandise.row', [
              'merchandise' => $merchandise,
              'name' => $merchandise->name,
            ])
        @endforeach
    </table>
@endsection
