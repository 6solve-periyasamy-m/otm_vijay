@extends('layout.form', ['action' => route('events.update', ['event' => $event,]),])

@section('title', 'Update Event')

@section('form-body')
    @include('partials.models.events.form', [
      'name' => $event->name,
      'description' => $event->description,
      'starts_at' => $event->starts_at,
      'ends_at' => $event->ends_at,
      'booking_url' => $event->booking_url,
      'notes' => $event->notes,
    ])
@endsection
