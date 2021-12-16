@extends('layout.main')

@section('title', 'All Tour Categories')

@section('content')
    <a class="btn btn-primary" href="{{ route('tour-categories.create') }}">Create New</a>
    <table id="tourCategories" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($tourCategories as $tourCategory)
            @include('partials.models.tour_categories.row', [
              'tourCategory' => $tourCategory,
              'name' => $tourCategory->name,
            ])
        @endforeach
    </table>
@endsection
