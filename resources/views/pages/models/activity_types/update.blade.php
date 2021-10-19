@extends('layout.main')

@section('title', 'Update Activity Type')

@section('content')
    @include('partials.models.activity_types.form', ['action' => route('activity-types.update', ['activityType' => $activityType,]),
      'name' => $activityType->name,
    ])
@endsection
