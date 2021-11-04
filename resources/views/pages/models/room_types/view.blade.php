@extends('layout.main')

@section('title', 'View Room Type')

@section('content')
    Room Type Name: {{ $roomType->name }}<br/>
    Maximum Occupancy: {{ $roomType->maximum_occupancy }}<br/>
@endsection
