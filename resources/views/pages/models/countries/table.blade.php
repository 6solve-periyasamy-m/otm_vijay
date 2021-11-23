@extends('layout.main')

@section('title', 'All Countries')

@section('content')
    <a class="btn btn-primary" href="{{ route('countries.create') }}">Create New</a>
    <table id="country" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Numeric Code</th>
            <th scope="col">Alpha Code</th>
            <th scope="col">Dialing Prefix</th>
            <th scope="col">Currency</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($countries as $country)
            @include('partials.models.countries.row', [
              'country' => $country,
              'name' => $country->name,
              'numeric_code' => $country->numeric_code,
              'alpha_code' => $country->alpha_code,
              'dialing_code' => $country->dialing_code,
              'currency' => $country->getCurrenciesList(),
            ])
        @endforeach
    </table>
@endsection
