@extends('layout.master')

@section('title', 'All Activities')

@section('content')
@can('create', \App\Models\Activity\Activity::class)
<x-admin.section.card>
    <a class="btn btn-primary float-end" href="{{ route('activities.create') }}">
        {{ Icon::create() }}
        <span>Create New</span>
    </a>
</x-admin.section.card>
@endcan
<x-admin.section.card>
    <table style="width: 100%;" class="datatable table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Activity Type</th>
            <th scope="col">Location</th>
            <th scope="col">Description</th>
            <th scope="col">Notes</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($activities as $activity)
            <tr>
                <td><a href="{{ route('activities.view', ['activity' => $activity,]) }}">{{ $activity->name }}</a></td>
                <td>{{ $activity->activityType->name }}</td>
                <td>{{ $activity->address }}</td>
                <td>{{ $activity->description }}</td>
                <td>{{ $activity->internal_notes }}</td>
                <td class="actions">
                    @can('update', \App\Models\Activity\Activity::class)
                        <a href="{{route('activities.edit', ['activity' => $activity,])}}" title="Edit" class="btn btn-sm btn-outline-success mb-1">
                            {{ Icon::edit() }}
                        </a>
                    @else
                        <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::edit() }}
                            </span>
                    @endcan
                    @can('delete', \App\Models\Activity\Activity::class)
                        <a href="#" class="btn btn-sm btn-outline-danger mb-1"  title="Delete"
                           onclick="event.preventDefault();document.getElementById('activity-{{ $activity->id }}-delete').submit();">
                            {{ Icon::delete() }}
                        </a>
                        <form id="activity-{{ $activity->id }}-delete"
                              action="{{ route('activities.delete', ['activity' => $activity,]) }}" method="POST"
                              style="display: none;">{{ csrf_field() }}</form>
                    @else
                        <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::delete() }}
                            </span>
                    @endcan
                </td>
            </tr>
        @endforeach
    </table>
</x-admin.section.card>
@endsection
