@extends('layout.main')

@section('title', 'Create Ticket Types')

@section('content')
  @include('partials.models.ticket_types.form', ['action' => route('ticket_types.store'),])
@endsection
