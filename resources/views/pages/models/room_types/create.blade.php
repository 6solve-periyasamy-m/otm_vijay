@extends('layout.main')

@section('title', 'Create Room Type')

@section('content')
    @include('partials.models.room_types.form', ['action' => route('room-types.store'),])
@endsection
