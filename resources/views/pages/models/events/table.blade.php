@extends('layout.main')

@section('title', 'Update Events')

@section('content')
<a class="btn btn-primary" href="{{ route('events.create') }}">Create New</a><table id="event" style="width: 100%;" class="table table-striped">
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
      'event_title' => $event->event_title,
      'event_description' => $event->event_description,
      'event_start_date' => $event->event_start_date,
      'event_end_date' => $event->event_end_date,
      'booking_url' => $event->booking_url,
      'notes' => $event->notes,
    ])
  @endforeach
</table>
@endsection
