@extends('layout.form', ['action' => route('activities.update', ['activity' => $activity,]),])

@section('title', 'Update Activity')

@section('form-body')
    @include('partials.models.activities.form', [
      'activity_type_id' => $activity->activity_type_id,
      'location_id' => $activity->location_id,
      'name' => $activity->name,
      'description' => $activity->description,
      'notes' => $activity->notes,
    ])
@endsection
