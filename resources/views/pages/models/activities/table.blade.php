@extends('layout.main')

@section('title', 'All Activities')

@section('content')
    <a class="btn btn-primary" href="{{ route('activities.create') }}">Create New</a>
    <table id="activity" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Title</th>
            <th scope="col">Activity Type</th>
            <th scope="col">Location</th>
            <th scope="col">Description</th>
            <th scope="col">Notes</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($activities as $activity)
            @include('partials.models.activities.row', [
              'activity' => $activity,
              'title' => $activity->title,
              'description' => $activity->description,
              'notes' => $activity->notes,
            ])
        @endforeach
    </table>
@endsection
