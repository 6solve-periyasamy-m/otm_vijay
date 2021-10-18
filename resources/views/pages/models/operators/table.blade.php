@extends('layout.main')

@section('title', 'All Operators')

@section('content')
    <a class="btn btn-primary" href="{{ route('operators.create') }}">Create New</a>
    <table id="operator" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Notes</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($operators as $operator)
            @include('partials.models.operators.row', [
              'operator' => $operator,
              'name' => $operator->name,
              'notes' => $operator->notes,
            ])
        @endforeach
    </table>
@endsection
