@extends('layout.form', ['action' => route('activities.update', ['activity' => $activity,]),'multipart' => true,])

@section('title', 'Update Activity')

@section('form-body')
    @include('partials.models.activities.form', [
      'activity_type_id' => $activity->activity_type_id,
      'address' => $activity->address,
      'name' => $activity->name,
      'image_url' => $activity->image_url,
      'description' => $activity->description,
      'currency' => $activity->currency,
      'notes' => $activity->internal_notes,
    ])
@endsection
