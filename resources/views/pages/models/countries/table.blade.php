@extends('layout.main')

@section('title', 'All Countries')

@section('content')
    <a class="btn btn-primary" href="{{ route('countries.create') }}">Create New</a>
    <table id="country" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Code</th>
            <th scope="col">Currency</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($countries as $country)
            @include('partials.models.countries.row', [
              'country' => $country,
              'name' => $country->name,
              'code' => $country->code,
              'currency' => $country->currency,
            ])
        @endforeach
    </table>
@endsection
