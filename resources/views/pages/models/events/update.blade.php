@extends('layout.main')

@section('title', 'Update Event')

@section('content')
    @include('partials.models.events.form', ['action' => route('events.update', ['event' => $event,]),
      'event_title' => $event->event_title,
      'event_description' => $event->event_description,
      'event_start_date' => $event->event_start_date,
      'event_end_date' => $event->event_end_date,
      'booking_url' => $event->booking_url,
      'notes' => $event->notes,
    ])
@endsection
