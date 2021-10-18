@extends('layout.main')

@section('title', 'View Location')

@section('content')
    Region Id: {{ $location->region_id }}<br/>
    Location Type Id: {{ $location->location_type_id }}<br/>
    Name: {{ $location->name }}<br/>
    Address: {{ $location->address }}<br/>
@endsection
