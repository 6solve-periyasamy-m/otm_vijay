@extends('layout.master')

@section('title', 'All Events')

@php
/**
 * @var \App\Models\Tour\Event[] $events
 */
@endphp

@section('content')
@can('create', \App\Models\Tour\Event::class)
<x-admin.section.card>
    <a class="btn btn-primary float-end" href="{{ route('events.create') }}">
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
            <th scope="col">Description</th>
            <th scope="col">Start Date</th>
            <th scope="col">End Date</th>
            <th scope="col">Event Category</th>
            <th scope="col">Notes</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($events as $event)
            <tr>
                <td><a href="{{route('events.view', ['event' => $event,])}}" class="link link-primary">{{ $event->name }}</a></td>
                <td>{{ $event->description }}</td>
                <td>{{ f_date($event->starts_at) }}</td>
                <td>{{ f_date($event->ends_at) }}</td>
                <td>{{ $event->event_category->label() }}</td>
                <td>{{ $event->notes }}</td>
                <td>
                    @can('update', \App\Models\Tour\Event::class)
                        <a href="{{route('events.edit', ['event' => $event,])}}" title="Edit" class="btn btn-sm btn-outline-success mb-1">
                            {{ Icon::edit() }}
                        </a>
                    @else
                        <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::edit() }}
                            </span>
                    @endcan
                    @can('delete', \App\Models\Tour\Event::class)
                        <a href="javascript:$('#events-{{ $event->id }}-delete').submit()"  title="Delete" class="btn btn-sm btn-outline-danger mb-1">
                            {{ Icon::delete() }}
                        </a>
                        <form id="events-{{ $event->id }}-delete"
                              action="{{ route('events.delete', ['event' => $event,]) }}" method="POST"
                              style="display: none;">{{ csrf_field() }}</form>
                    @else
                        <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::delete() }}
                            </span>
                    @endcan
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</x-admin.section.card>
@endsection
