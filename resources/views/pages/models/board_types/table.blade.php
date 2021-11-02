@extends('layout.main')

@section('title', 'All Board Types')

@section('content')
    <a class="btn btn-primary" href="{{ route('board-types.create') }}">Create New</a>
    <table id="boardType" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Board Type Name</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($boardTypes as $boardType)
            @include('partials.models.board_types.row', [
              'boardType' => $boardType,
              'name' => $boardType->name,
            ])
        @endforeach
    </table>
@endsection
