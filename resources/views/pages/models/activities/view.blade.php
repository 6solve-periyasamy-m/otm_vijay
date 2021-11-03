@extends('layout.main')

@section('title', 'View Activity')

@section('content')
    Activity Type Id: {{ $activity->activity_type_id }}<br/>
    Location Id: {{ $activity->location_id }}<br/>
    Name: {{ $activity->name }}<br/>
    Description: {{ $activity->description }}<br/>
    Notes: {{ $activity->notes }}<br/>
@endsection
