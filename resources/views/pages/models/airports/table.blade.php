@extends('layout.master')

@section('title', 'All Airports')

@section('content')
<div class="card">
    <div class="card-body">
        <a class="btn btn-primary float-end" href="{{ route('airports.create') }}">
            {{ Icon::create() }}
            <span>Create New</span>
        </a>
    </div>
</div>
<div class="card">
    <div class="card-body">                
        <table id="airport" style="width: 100%;" class="datatable table table-striped">
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
    </div>
</div>
@endsection
