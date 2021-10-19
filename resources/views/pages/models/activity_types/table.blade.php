@extends('layout.main')

@section('title', 'All Activity Types')

@section('content')
    <a class="btn btn-primary" href="{{ route('activity-types.create') }}">Create New</a>
    <table id="activityType" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($activityTypes as $activityType)
            @include('partials.models.activity_types.row', [
              'activityType' => $activityType,
              'name' => $activityType->name,
            ])
        @endforeach
    </table>
@endsection
