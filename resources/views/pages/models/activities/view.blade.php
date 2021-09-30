@extends('layout.main')

@section('title', 'View Activities')

@section('content')
Activity Type Id: {{ $activity->activity_type_id }}<br />
Location Id: {{ $activity->location_id }}<br />
Title: {{ $activity->title }}<br />
Description: {{ $activity->description }}<br />
Notes: {{ $activity->notes }}<br />
@endsection
