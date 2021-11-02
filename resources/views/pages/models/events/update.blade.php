@extends('layout.master')

@section('title', 'Update Event')

@section('content')
    @include('partials.models.events.form', ['action' => route('events.update', ['event' => $event,]),
      'name' => $event->name,
      'description' => $event->description,
      'starts_at' => $event->starts_at,
      'ends_at' => $event->ends_at,
      'booking_url' => $event->booking_url,
      'notes' => $event->notes,
    ])
@endsection
