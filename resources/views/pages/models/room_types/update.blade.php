@extends('layout.form', ['action' => route('room-types.update', ['roomType' => $roomType,]),])

@section('title', 'Update Room Type')

@section('form-body')
    @include('partials.models.room_types.form', [
      'name' => $roomType->name,
      'maximum_occupancy' => $roomType->maximum_occupancy,
    ])
@endsection
