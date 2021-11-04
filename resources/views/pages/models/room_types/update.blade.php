@extends('layout.main')

@section('title', 'Update Room Type')

@section('content')
    @include('partials.models.room_types.form', ['action' => route('room-types.update', ['roomType' => $roomType,]),
      'name' => $roomType->name,
      'maximum_occupancy' => $roomType->maximum_occupancy,
    ])
@endsection
