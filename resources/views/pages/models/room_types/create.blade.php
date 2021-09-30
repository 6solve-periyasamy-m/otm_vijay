@extends('layout.main')

@section('title', 'Create Room Types')

@section('content')
  @include('partials.models.room_types.form', ['action' => route('room_types.store'),])
@endsection
