@extends('layout.main')

@section('title', 'All Hat Sizes')

@section('content')
    <a class="btn btn-primary" href="{{ route('hat-sizes.create') }}">Create New</a>
    <table id="hatSize" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($hatSizes as $hatSize)
            @include('partials.models.hat_sizes.row', [
              'hatSize' => $hatSize,
              'name' => $hatSize->name,
            ])
        @endforeach
    </table>
@endsection
