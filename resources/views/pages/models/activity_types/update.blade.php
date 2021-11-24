@extends('layout.form', ['action' => route('activity-types.update', ['activityType' => $activityType,]),])

@section('title', 'Update Activity Type')

@section('form-body')
    @include('partials.models.activity_types.form', [
      'name' => $activityType->name,
    ])
@endsection
