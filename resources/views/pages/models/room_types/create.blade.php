@extends('layout.form', ['action' => route('room-types.store'),])

@section('title', 'Create Room Type')

@section('form-body')
    @include('partials.models.room_types.form')
@endsection
