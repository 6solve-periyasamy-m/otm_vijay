@extends('layout.main')

@section('title', 'Update Room Types')

@section('content')
  @include('partials.models.room_types.form', ['action' => route('room_types.update', ['roomType' => $roomType,]),
    'room_type_name' => $roomType->room_type_name,
    'maximum_occupancy' => $roomType->maximum_occupancy,
  ])
@endsection
