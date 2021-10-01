@extends('layout.main')

@section('title', 'View Room Types')

@section('content')
    Room Type Name: {{ $roomType->room_type_name }}<br/>
    Maximum Occupancy: {{ $roomType->maximum_occupancy }}<br/>
@endsection
