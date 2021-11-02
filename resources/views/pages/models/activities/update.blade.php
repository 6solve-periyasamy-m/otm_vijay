@extends('layout.master')

@section('title', 'Update Activity')

@section('content')
    @include('partials.models.activities.form', ['action' => route('activities.update', ['activity' => $activity,]),
      'activity_type_id' => $activity->activity_type_id,
      'location_id' => $activity->location_id,
      'name' => $activity->name,
      'description' => $activity->description,
      'notes' => $activity->notes,
    ])
@endsection
