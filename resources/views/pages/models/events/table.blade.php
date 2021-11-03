@extends('layout.main')

@section('title', 'All Events')

@section('content')
    <a class="btn btn-primary" href="{{ route('events.create') }}">Create New</a>
    <table id="event" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Event Title</th>
            <th scope="col">Event Description</th>
            <th scope="col">Event Start Date</th>
            <th scope="col">Event End Date</th>
            <th scope="col">Booking Url</th>
            <th scope="col">Notes</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($events as $event)
            @include('partials.models.events.row', [
              'event' => $event,
              'name' => $event->name,
              'description' => $event->description,
              'starts_at' => $event->starts_at,
              'ends_at' => $event->ends_at,
              'booking_url' => $event->booking_url,
              'notes' => $event->notes,
            ])
        @endforeach
    </table>
@endsection
